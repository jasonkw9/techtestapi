<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretLabChange extends Model
{
    use HasFactory;

    protected $table = 'secret_lab_change';

    protected $fillable = [
        'key', 'old_value', 'updated_value'
    ];
}
