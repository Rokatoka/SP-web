<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['prefix' => 'public'], function() {
    Route::get('/users','UserController@accounts');
    Route::post('/reset-pass/{id}','UserController@resetPass');
    Route::post('/check-reset-code','UserController@checkResetCode');
    Route::post('/send-reset-code/{id}','UserController@sendResetCode');
    Route::post('/user/register', 'UserController@register');
    Route::get('/verify/{email}', 'UserController@verifyEmail')->name('verifyEmail');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user/{id}', 'UserController@item');

    Route::get('/user', 'UserController@authenticated');
    Route::get('/patient-info/{id}', 'UserController@getPatientData');


    Route::post('/doctor/departments', 'DoctorController@getDepartments');
    Route::post('/doctor/hospitals', 'DoctorController@getHospitals');

    Route::post('/doctor/save-info', 'DoctorController@saveInfo');
    Route::get('/doctor/get/{id}', 'DoctorController@item');
    Route::post('/doctor/get-appointments', 'DoctorController@appointmentList');

    Route::get('/doctor/get-appointment-history/{id}', 'DoctorController@appointmentHistory');
    Route::post('/doctor/appointment-customize', 'DoctorController@appointmentCustomize');


    Route::get('/patient/get-appointment-history/{id}', 'PatientController@appointmentHistory');
    Route::get('/patient/cancel-appointment/{id}', 'PatientController@appointmentCancel');


    Route::get('/statistics', 'MainController@statistics');

    Route::get('/hospitals/get', 'PatientController@getHospitals');
    Route::post('/hospitals/departments/get', 'PatientController@getDepartments');
    Route::post('/hospitals/departments/doctors/get', 'PatientController@getDepartmentDoctors');

    Route::post('/appointment/department-get', 'PatientController@appointmentDepartment');
    Route::post('/appointment/doctor-type-get', 'PatientController@appointmentPosition');
    Route::post('/appointment/doctor-get', 'PatientController@appointmentDoctor');

    Route::post('/appointment/schedule', 'PatientController@appointmentSchedule');
    Route::post('/appointment/send', 'PatientController@appointmentSend');
    Route::post('/appointment/get-list', 'PatientController@appointmentList');


    Route::group(['middleware' => ['isEditor']], function () {


        Route::get('/users', 'UserController@items');

        Route::post('/user-save', 'UserController@save');
        Route::delete('/user-delete/{id}', 'UserController@delete');
        Route::post('/user-photo-save', 'UserController@savePhoto');

        Route::get('/users-all', 'UserController@all');


    });

    Route::group(['middleware' => ['isViewer']], function () {
        Route::get('/dashboard', 'MainController@dashboard');
    });
    
    Route::get('/main/data', 'MainController@data');


});
