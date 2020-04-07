<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ranking;

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
