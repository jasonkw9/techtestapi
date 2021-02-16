<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretLab extends Model
{
    use HasFactory;

    protected $table = 'secret_lab';

    protected $fillable = [
        'key','value'
    ];
}
