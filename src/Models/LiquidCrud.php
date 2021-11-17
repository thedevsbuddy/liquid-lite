<?php

namespace Devsbuddy\LiquidLite\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidCrud extends Model
{
    use HasFactory;

    protected $table = 'liquid_cruds';

    protected $guarded = ['id'];

    protected $casts = [
        'controllers' => 'object',
        'menu' => 'object',
        'payload' => 'object'
    ];
}
