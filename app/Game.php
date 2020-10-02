<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        "name"
    ];

    public function armies()
    {
        return $this->belongsToMany("App\Army", 'pivots', 'game_id'/*, 'army_id'*/);
    }

    public function strategies()
    {
        return $this->hasOneThrough('App\Army', 'App\Strategy');
    }

}
