<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Player;

class Ranking extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
