<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'time_at_doctor',
        'appointment_id',
        'diagnosis',
        'analysis',
        'treatment'
    ];


    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function patient(){
        return $this->belongsTo('App\Patient');
    }

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function appointment(){
        return $this->belongsTo('App\Appointment');
    }
}
