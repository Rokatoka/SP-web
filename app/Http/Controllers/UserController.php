<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\User;
use App\Patient;
use App\php;
use App\UserPasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Manager;
use Log;
use Mail;
use Validator;

class UserController extends Controller
{

    public function authenticated(Request $request)
    {
        $users = User::where('id', $request->user()->id)
            ->with('role')
            ->get();
        return $users;
    }


    public function items(Request $request)
    {
        Log::info($request);
        if($request->get('search_text') != NULL){
            if($request->get('field') == 'phone'){
                return User::with('role')->orderBy('id', 'desc')
                    ->where('phone', 'LIKE', '%'.$request->get('search_text').'%')->paginate(20);
            }else if($request->get('field') == 'email'){
                return User::with('role')->orderBy('id', 'desc')
                    ->where('email', 'LIKE', '%'.$request->get('search_text').'%')->paginate(20);
            }else if($request->get('field') == 'name'){
                return User::with('role')->orderBy('id', 'desc')
                    ->where('first_name', 'LIKE', '%'.$request->get('search_text').'%')
                    ->orWhere('last_name', 'LIKE', '%'.$request->get('search_text').'%')
                    ->orWhere('patronymic', 'LIKE', '%'.$request->get('search_text').'%')->paginate(20);
            }
        }else{
            return User::with('role')->orderBy('id', 'desc')->paginate(20);
        }

    }

    public function all(Request $request)
    {
        
        return User::whereHas('role', function ($query) {
                $query->where('name', 'administrator');
        })->get();
    }

    public function item($id)
    {

        return User::where('id', $id)
            ->with([
                'role',
            ])->first();

    }


    public function save(request $request)
    {
        Log::info($request);
        $messages = array(
            'first_name.required' => 'Name is required',
            'last_name.required' => 'Surname is required',
            'patronymic.required' => 'Patronymic is required',
            'phone.required' => 'Phone Number required',
            'min' => 'Minimum numbers is :min',
            'email.required' => 'Email is required',
            'email' => 'Field should be valid email'
        );
        $userValidator = Validator::make($request->get('user'), User::rules(), $messages);
        if($userValidator->fails()){
            return response()->json(['errors'=>$userValidator->errors()], 422);
        }

        $us = $request->get('user');
        $id = $us['id'] ? $us['id'] : 0;


        $user = $id ? User::find($id) : new User();

        $user->first_name = $us['first_name'];
        $user->last_name = $us['last_name'];
        $user->patronymic = $us['patronymic'];
        $user->email = $us['email'];
        $user->phone = $us['phone'];
        $user->iin = $us['iin'];
        $user->role_id = $request['user']['role_id']['id'];
        $user->password = bcrypt($us['password']);

        $user->validator = 1;
        $user->save();

        $age_gender = $this->calculateBirthDateGender($us['iin']);

        if($request['user']['role_id']['id'] == 2){
            $doctor = new Doctor();
            $doctor->user_id = $user->id;
            $doctor->birth_date = $age_gender[0];
            $doctor->gender = $age_gender[1];
            $doctor->save();
        }

    }

    public function register(Request $request){

        Log::info($request);
        $messages = array(
            'first_name.required' => 'Name is required',
            'last_name.required' => 'Surname is required',
            'patronymic.required' => 'Patronymic is required',
            'phone.required' => 'Phone Number required',
            'iin.required' => 'IIN required',
            'min' => 'Minimum numbers is :min',
            'email.required' => 'Email is required',
            'email' => 'Field should be valid email'
        );
        $userValidator = Validator::make($request->get('user'), User::rules(), $messages);
        if($userValidator->fails()){
            return response()->json(['errors'=>$userValidator->errors()], 422);
        }
        $data = $request->get('user');
        $age_gender = $this->calculateBirthDateGender($data['iin']);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $password = '';
        for ($i = 0; $i < 6; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        Log::info($password);
        $sameUser = User::where('phone', '=', $data['phone'])
            ->first();
        if(!empty($sameUser)){
            return response()->json(['errors'=>'User already exists'], 422);
        }
        $newUser = new User;
        $newUser->first_name = $data['first_name'];
        $newUser->last_name = $data['last_name'];
        $newUser->patronymic = $data['patronymic'];
        $newUser->phone = $data['phone'];
        $newUser->iin = $data['iin'];
        $newUser->email = $data['email'];
        $newUser->role_id = 3;
        $newUser->password = bcrypt($password);
        $newUser->validator = 0;

        $newUser->save();


        $patient = new Patient();
        $patient->user_id = $newUser->id;
        $patient->birth_date = $age_gender[0];
        $patient->gender = $age_gender[1];
        $patient->save();

        $to_email = $data['email'];
        $to_name = $data['first_name'].' '.$data['last_name'].' '.$data['patronymic'];
        $data = array('name'=>$to_name, 'password'=>$password, 'email'=>$data['email']);
        Mail::send('emails.register', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('myHealth Registration');
            $message->from('recordshealth75@gmail.com','myHealth Team');
        });

    }

    public function verifyEmail($email){
        Log::info("ieyrhgiuerh");
        Log::info($email);
        $user = User::where('email',$email)->where('validator', 0)->first();
        $user->validator = 1;
        $user->save();
        return redirect()->route('login');
    }

    public function savePhoto(request $request)
    {

        $id = $request->get('id') ? $request->get('id') : 0;

        $this->validate($request, [
            'photo' => 'image64:jpeg,jpg,png',
        ]);

        $user = User::find($id);
        if($request->get('photo'))
            $user->photo = $request->get('photo');
        $user->save();

    }


    public function delete($id)
    {

        $user = User::find($id);
        User::destroy($id);

    }

    public function accounts(request $request) {
        $phone = $request->get('phone');

        $users = User::where('phone','like',$phone)
            ->where('validator', 1)
            ->with(['role'])
            ->get();
        Log::info($users);

        return response()->json(['status' => 'success', 'data' => $users], 200);
    }


    public function sendResetCode($id) {
        $reset = UserPasswordReset::create([
            'user_id' => $id,
            'code' => rand(1000,9999),
            'token' => md5($id.Carbon::now()->toDateTimeString()),
            'expires_at' => Carbon::now()->addMinutes(15)->toDateTimeString()
        ]);

        $user = User::find($id);
        sms_send($user->phone,'Код подтверждения: '.$reset->code);

        return response()->json(['status' => 'success', 'token' => $reset->token], 200);
    }

    public function checkResetCode(request $request) {
        $reset = UserPasswordReset::where('token',$request->get('token'))
            ->first();
        if($reset->code == $request->get('code')) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'failed'], 422);
        }
    }

    public function resetPass($id, request $request) {
        $this->validate($request, [
            'password' => 'string|min:6|confirmed',
        ]);
        $user = User::find($id);
        $user->password = bcrypt($request->get('password'));
        $nocrypt=$request->get('password');
        $user->save();
        sms_send($user->phone, 'Ваш пароль: '.$nocrypt);
        return response()->json(['status' => 'success'], 200);
    }

    public static function deleteTokens() {
        UserPasswordReset::where('expires_at','<',Carbon::now()->toDateTimeString())
            ->delete();
    }

    public static function getPatientData($id){
        Log::info("CONTROLLER");
        Log::info($id);
        $patient = Patient::where('user_id', '=', $id)->first();
        return $patient;
    }

    public function calculateBirthDateGender($iin) {
        $return = [];
        $age_year = substr($iin, 0, 2);
        $age_month = substr($iin, 2, 2);
        $age_day = substr($iin, 4, 2);
        $gender = substr($iin, 6, 1);
        $age_tot = "";
        $gen = "";
        if($gender == 1){
            $age_tot = $age_tot . "18";
            $gen = "M";
        }else if($gender == 2){
            $age_tot = $age_tot . "18";
            $gen = "F";
        }else if($gender == 3){
            $age_tot = $age_tot . "19";
            $gen = "M";
        }else if($gender == 4){
            $age_tot = $age_tot . "19";
            $gen = "F";
        }else if($gender == 5){
            $age_tot = $age_tot . "20";
            $gen = "M";
        }else if($gender == 6){
            $age_tot = $age_tot . "20";
            $gen = "F";
        }

        $age_tot = $age_tot . $age_year;
        $age_tot = $age_tot . ".";
        $age_tot = $age_tot . $age_month;
        $age_tot = $age_tot . ".";
        $age_tot = $age_tot . $age_day;

        array_push($return, $age_tot);
        array_push($return, $gen);

        return $return;

    }
}
