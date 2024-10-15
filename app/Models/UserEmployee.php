<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEmployee extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "user_employees";
    protected $fillable = [
        'id',
        'user_id',
        'employee_id',
        'created_at',
        'updated_at',
    // created_at  timestamp(0),
    // updated_at  timestamp(0),
    ];
}
