<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{

    protected $table = 'hospitals';

    protected $fillable = [
        'name',
        'address'
    ];


    public function departments(){
        return $this->hasMany('App\Department', 'hospital_id');
    }
}
