<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'user_id',
        'age',
        'gender',
    ];

    public static function rules($id = 0)
    {
        return [
            'age' => 'required|integer',
            'gender' => 'required| in:M,F'
        ];
    }


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function histories(){
        return $this->hasMany('App\History', 'patient_id');
    }

    public function appointments(){
        return $this->hasMany('App\Appointment', 'patient_id');
    }
}
