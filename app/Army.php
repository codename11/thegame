<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $fillable = [
        "name", "units"
    ];

    public function armies()
    {
        return $this->belongsToMany("App\Game", 'pivots', 'army_id'/*, 'game_id'*/);
    }

    public function strategy(){
        return $this->hasOne("App\Strategy", 'army_id');
    }

}
