<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ranking;

class Player extends Model
{
    protected $fillable = [
        'name',
        'score'
    ];
    
    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }
}
