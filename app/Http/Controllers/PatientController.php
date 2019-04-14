<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Department;
use App\History;
use App\Hospital;
use App\Positions;
use App\User;
use Illuminate\Http\Request;
use Log;
use App\Doctor;
use App\Schedule;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function appointmentDepartment(Request $request){
        $departments = Department::where('hospital_id', '=', $request->get('hospital'))->get();
        return $departments;
    }

    public function appointmentPosition(Request $request){
        $dep = Department::where('id', '=', $request->get('department'))->first();
        $position = Positions::where('id', '=', $dep->position_id)->get();
        return $position;
    }


    public function appointmentDoctor(Request $request){
        $doctors = Doctor::where('department_id', $request->get('department'))->where('position_id', '=', $request->get('position'))->where('position_id', '!=', null)->get();
        $users = [];
        foreach($doctors as $doc){
            $user = User::where('id', '=', $doc->user_id)->first();
            array_push($users, $user);
        }
        return $users;
    }

    public function appointmentSchedule(Request $request){
        $doctor = Doctor::where('user_id', '=', $request->get('doctor'))->first();
        $schedule = ScheDule::where('doctor_id', '=', $doctor->id)->paginate(2);
        $appointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'approved')->get();
        return response()->json(['schedule'=> $schedule, 'appointments'=>$appointments], 200 );
    }

    public function appointmentSend(Request $request){
        Log::info($request);
        $doctor = Doctor::where('user_id', '=', $request->get('doctor'))->first();
        $appointemnt = new Appointment();
        $appointemnt->patient_id = $request->get('patient');
        $appointemnt->doctor_id = $doctor->id;
        $appointemnt->department_id = $request->get('department');
        $appointemnt->date = $request->get('date');
        $appointemnt->status = 'approved';
        $appointemnt->time = $request->get('time');
        $appointemnt->save();

        $history = new History();
        $history->patient_id = $request->get('patient');
        $history->doctor_id = $doctor->id;
        $history->department_id = $request->get('department');
        $history->appointment_id = $appointemnt->id;
        $history->save();
        return;

    }

    public function appointmentList(Request $request){
        Log::info($request);
        if($request['filter'] != NULL){
            if($request['filter']['field'] == 'name'){
                $appointments = Appointment::where('patient_id', '=', $request->get('user'))->with('doctor.user', 'patient.user')
                    ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')->join('users', 'users.id', '=', 'doctors.user_id')
                    ->where('users.first_name', 'LIKE', '%'.$request['filter']['search_text'].'%')
                    ->orWhere('users.last_name', 'LIKE', '%'.$request['filter']['search_text'].'%')
                    ->orWhere('users.patronymic', 'LIKE', '%'.$request['filter']['search_text'].'%')->get();
            }else if($request['filter']['field'] == 'date'){
                $appointments = Appointment::with('doctor.user', 'patient.user')->where('patient_id', '=', $request->get('user'))
                    ->where(function ($query) use($request){
                        $query->where('date', 'LIKE', '%'.$request['filter']['search_text'].'%')
                            ->orWhere('time', 'LIKE', '%'.$request['filter']['search_text'].'%');
                    })->get();
            }else if($request['filter']['field'] == 'status'){
                if($request['filter']['status'] == 'approved'){
                    $appointments = Appointment::where('patient_id', '=', $request->get('user'))->where('status', '=', 'approved')->with('doctor.user', 'patient.user')->get();
                }else if($request['filter']['status'] == 'canceled'){
                    $appointments = Appointment::where('patient_id', '=', $request->get('user'))->where('status', '=', 'canceled')->with('doctor.user', 'patient.user')->get();
                }else if($request['filter']['status'] == 'done'){
                    $appointments = Appointment::where('patient_id', '=', $request->get('user'))->where('status', '=', 'done')->with('doctor.user', 'patient.user')->get();
                }
            }
        }else{
            $appointments = Appointment::where('patient_id', '=', $request->get('user'))->with('doctor.user', 'patient.user')->get();
        }

        $date = Carbon::now();
        foreach ($appointments as $app){
            $dateApp = $app['date'];
            $timeApp = $app['time'];
            $total = $dateApp . ' ' . $timeApp;
            if(strtotime($total) < $date->getTimestamp()){
                if($app->status == 'approved'){
                    $app->status = 'missed';
                    $app->save();
                }
            }
        }
        return $appointments;
    }


    public function appointmentHistory($id){
        $history = History::where('appointment_id', '=', $id)->with('doctor.user', 'department.hospital', 'appointment')->first();
        return $history;
    }

    public function appointmentCancel($id){
        $appointment = Appointment::where('id', '=', $id)->first();
        $appointment->status = 'canceled';
        $appointment->save();
        $history = History::where('appointment_id', '=', $id)->with('doctor.user', 'department.hospital', 'appointment')->first();
        return $history;
    }

    public function getHospitals(){
        $hospitals = Hospital::all();
        return $hospitals;
    }

    public function getDepartments(Request $request){
        $departments = Department::where('hospital_id', $request->get('hospital'))->get();
        return $departments;
    }

    public function getDepartmentDoctors(Request $request){
        $doctors = Doctor::where('department_id', $request->get('department'))->with(['position', 'user'])->get();
        return $doctors;
    }
}
