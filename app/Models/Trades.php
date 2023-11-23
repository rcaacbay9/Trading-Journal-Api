<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trades extends Model
{
    use HasFactory;
    protected $fillable = [
        'pairs',
        'time_executed',
        'session',
        'position',
        'result',
        'risk_reward',
        'comment',
    ];
}
