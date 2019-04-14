<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'position_id',
        'department_id',
        'schedule_id'
    ];


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function histories(){
        return $this->hasMany('App\History', 'doctor_id');
    }

    public function appointments(){
        return $this->hasMany('App\Appointment', 'doctor_id');
    }

    public function position(){
        return $this->belongsTo('App\Positions');
    }

}
