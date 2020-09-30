<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    protected $fillable = [
        "army_id", "game_id"
    ];

    public function armies(){
        return $this->belongsTo("App\Army","army_id");
    }

    public function games(){
        return $this->belongsTo("App\Game","game_id");
    }

}
