<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'name'
    ];

    public function doctors(){
        return $this->hasOne('App/Doctor', 'position_id');
    }
}
