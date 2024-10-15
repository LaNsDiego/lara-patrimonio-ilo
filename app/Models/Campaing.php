<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Campaing extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;


    protected $fillable = [
        'id',
        'name',
    ];

    public function programations()
    {
        return $this->hasMany(Programation::class)->orderBy('id', 'desc');
    }

     //Activity Log
     public function getActivitylogOptions(): LogOptions
     {
         return LogOptions::defaults()
             ->logOnly([
                 'name',
             ])
            ->useLogName('Campaing Log');
    }
}
