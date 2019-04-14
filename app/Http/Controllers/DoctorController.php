<?php

namespace App\Http\Controllers;

use App\Department;
use App\Doctor;
use App\Hospital;
use Illuminate\Http\Request;
use Log;
use App\Schedule;
use App\Appointment;
use App\History;
use Carbon\Carbon;

class DoctorController extends Controller
{

    public function item($id){
        return Doctor::where('user_id', '=', $id)->first();
    }

    public function getDepartments(Request $request){
        $departments = Department::where('position_id', '=', $request->get('position'))->get();
        return $departments;
    }

    public function getHospitals(Request $request){
        $dep = Department::where('id', '=', $request->get('department'))->first();

        $hospitals = Hospital::where('id', '=', $dep->hospital_id)->get();

        return $hospitals;
    }


    public function saveInfo(Request $request){
        $department = $request->get('department');
        $user = $request->get('user');
        $schedule = $request->get('schedule');
        $position = $request->get('position');

        $doctor = Doctor::where('user_id', '=', $user)->first();
        $doctor->position_id = $position;
        $doctor->department_id = $department;


        $schedule_from = new Schedule();
        $schedule_from->doctor_id = $doctor->id;
        if($schedule['monday']['isMonday'] == 'true'){
            $schedule_from->Monday = $schedule['monday']['from'];
        }else{
            $schedule_from->Monday = '';
        }
        if($schedule['tuesday']['isTuesday'] == 'true'){
            $schedule_from->Tuesday = $schedule['tuesday']['from'];
        }else{
            $schedule_from->Tuesday = '';
        }
        if($schedule['wednesday']['isWednesday'] == 'true'){
            $schedule_from->Wednesday = $schedule['wednesday']['from'];
        }else{
            $schedule_from->Wednesday = '';
        }
        if($schedule['thursday']['isThursday'] == 'true'){
            $schedule_from->Thursday = $schedule['thursday']['from'];
        }else{
            $schedule_from->Thursday = '';
        }
        if($schedule['friday']['isFriday'] == 'true'){
            $schedule_from->Friday = $schedule['friday']['from'];
        }else{
            $schedule_from->Friday = '';
        }

        $schedule_from->save();

        $doctor->schedule_id = $schedule_from->id;


        $schedule_to = new Schedule();
        $schedule_to->doctor_id = $doctor->id;
        if($schedule['monday']['isMonday'] == 'true'){
            $schedule_to->Monday = $schedule['monday']['to'];
        }else{
            $schedule_to->Monday = '';
        }
        if($schedule['tuesday']['isTuesday'] == 'true'){
            $schedule_to->Tuesday = $schedule['tuesday']['to'];
        }else{
            $schedule_to->Tuesday = '';
        }
        if($schedule['wednesday']['isWednesday'] == 'true'){
            $schedule_to->Wednesday = $schedule['wednesday']['to'];
        }else{
            $schedule_to->Wednesday = '';
        }
        if($schedule['thursday']['isThursday'] == 'true'){
            $schedule_to->Thursday = $schedule['thursday']['to'];
        }else{
            $schedule_to->Thursday = '';
        }
        if($schedule['friday']['isFriday'] == 'true'){
            $schedule_to->Friday = $schedule['friday']['to'];
        }else{
            $schedule_to->Friday = '';
        }

        $schedule_to->save();

        $doctor->schedule_to_id = $schedule_to->id;

        $doctor->save();

    }

    public function appointmentList(Request $request){
        Log::info($request);
        if($request['filter'] != NULL){
            if($request['filter']['field'] == 'name'){
                $appointments = Appointment::where('doctor_id', '=', $request->get('doctor'))->with('doctor.user', 'patient.user')
                    ->join('patients', 'appointments.patient_id', '=', 'patients.id')->join('users', 'users.id', '=', 'patients.user_id')
                    ->where('users.first_name', 'LIKE', '%'.$request['filter']['search_text'].'%')
                        ->orWhere('users.last_name', 'LIKE', '%'.$request['filter']['search_text'].'%')
                        ->orWhere('users.patronymic', 'LIKE', '%'.$request['filter']['search_text'].'%')->get();
            }else if($request['filter']['field'] == 'date'){
                $appointments = Appointment::with('doctor.user', 'patient.user')->where('doctor_id', '=', $request->get('doctor'))
                    ->where(function ($query) use($request){
                        $query->where('date', 'LIKE', '%'.$request['filter']['search_text'].'%')
                            ->orWhere('time', 'LIKE', '%'.$request['filter']['search_text'].'%');
                    })->get();
            }else if($request['filter']['field'] == 'status'){
                if($request['filter']['status'] == 'approved'){
                    $appointments = Appointment::where('doctor_id', '=', $request->get('doctor'))->where('status', '=', 'approved')->with('doctor.user', 'patient.user')->get();
                }else if($request['filter']['status'] == 'canceled'){
                    $appointments = Appointment::where('doctor_id', '=', $request->get('doctor'))->where('status', '=', 'canceled')->with('doctor.user', 'patient.user')->get();
                }else if($request['filter']['status'] == 'done'){
                    $appointments = Appointment::where('doctor_id', '=', $request->get('doctor'))->where('status', '=', 'done')->with('doctor.user', 'patient.user')->get();
                }
            }
        }else{
            $appointments = Appointment::where('doctor_id', '=', $request->get('doctor'))->with('doctor.user', 'patient.user')->get();
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
        $history = History::where('appointment_id', '=', $id)->first();
        return $history;
    }

    public function appointmentCustomize(Request $request){
        $history = History::where('appointment_id', '=', $request->get('appointment'))->first();
        $history->diagnosis = $request->get('diagnosis');
        $history->analysis = $request->get('analysis');
        $history->treatment = $request->get('treatment');
        $history->time_at_doctor = Carbon::now();
        $history->save();
        $appointment = Appointment::where('id', '=', $request->get('appointment'))->first();
        $appointment->status = 'done';
        $appointment->save();
    }
}
