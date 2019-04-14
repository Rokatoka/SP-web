<?php
/**
 * Created by PhpStorm.
 * User: Yurituski
 * Date: 19.11.2018
 * Time: 23:24
 */

namespace App\Http\Controllers;


use App\Doctor;
use App\Hospital;
use App\Patient;
use App\Positions;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Request;

class MainController extends Controller
{

    public function data(Request $request)
    {

        $data = [];
        $data['roles'] = Role::all();

        $data['positions'] = Positions::all();
        $data['hospitals'] = Hospital::all();

        return $data;

    }

    public function statistics(){
        $users = User::where('role_id', '!=', 1)->count();
        $malesDoc = Doctor::where('gender', '=', 'M')->count();
        $malesPat = Patient::where('gender', '=', 'M')->count();
        $femalesDoc = Doctor::where('gender', '=', 'F')->count();
        $femalesPat = Patient::where('gender', '=', 'F')->count();
        $doctors = Doctor::all()->count();
        $patients = Patient::all()->count();
        $malesTot = $malesDoc + $malesPat;
        $femalesTot = $femalesDoc + $femalesPat;
        return response()->json(['total' => $users, 'malesTot' => $malesTot, 'femalesTot' => $femalesTot, 'doctors' => $doctors, 'patients' => $patients], 200);
    }
}