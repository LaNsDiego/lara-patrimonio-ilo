<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProgramationSchedule extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $table = "programation_schedules";
    protected $fillable = [
        "programation_id",
        "name",
        "shift",
        "start_time",
        "end_time",
        "map_route_id",
        "created_at",
        "updated_at"
    ];

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'programation_id',
                'name',
                'shift',
                'start_time',
                'end_time',
                'map_route_id',
                'created_at',
                'updated_at'
            ])
            ->useLogName('ProgramationSchedule Log');
    }
}
