<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'hospital_id',
        'name',
        'position_id'
    ];


    public function histories(){
        return $this->hasMany('App\History', 'department_id');
    }

    public function hospital(){
        return $this->belongsTo('App\Hospital');
    }
}
