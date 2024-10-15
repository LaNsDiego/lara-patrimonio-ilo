<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;


    protected $table = 'brands';
    protected $fillable = ['name'];

     //Activity Log
     public function getActivitylogOptions(): LogOptions
     {
         return LogOptions::defaults()
             ->logOnly([
                 'name',
             ])
            ->useLogName('Brand Log');
    }
}
