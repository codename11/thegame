<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    protected $fillable = [
        "army_id", "strategy"
    ];

    /*public function strategy(){
        return $this->belongsToMany("App\Army");
    }*/

}
