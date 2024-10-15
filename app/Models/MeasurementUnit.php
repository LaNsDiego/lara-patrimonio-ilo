<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MeasurementUnit extends Model
{
    use HasFactory,SoftDeletes, LogsActivity;

    protected $fillable = [
        'acronym',
        'name'
    ];

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'acronym',
                'name',
            ])
            ->useLogName('MeasurementUnit Log');
    }
    
}
