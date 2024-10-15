<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LocationType extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    
    const CONTAINER = 'CONTENEDORES';
    const VEHICLE = 'VEHICULOS';

    protected $fillable = [
        'name',
        'image',
    ];

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'image',
            ])
            ->useLogName('LocationType Log');
    }
}
