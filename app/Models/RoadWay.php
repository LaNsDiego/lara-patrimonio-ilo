<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RoadWay extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    
    protected $table = "road_ways";
    protected $fillable = [
        'name',
        'geojson',
        'point_center',
        'point_a',
        'point_b',
    ];

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'geojson',
                'point_center',
                'point_a',
                'point_b',
            ])
            ->useLogName('RoadWay Log');
    }
}
