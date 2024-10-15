<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ResourceMapAssignment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    const MAP_RESOURCE_SECTOR = 'SECTOR';
    const MAP_RESOURCE_LOCATION = 'UBICACION';
    const MAP_RESOURCE_ROAD = 'CAMINO';

    protected $table = "resource_map_assignments";
    protected $fillable = [
        'id',
        'type_map_resource',
        'route_map_id',
        'resource_id',
    ];

    public function road_way(){
        return $this->belongsTo(RoadWay::class,'resource_id');
    }

    public function sector(){
        return $this->belongsTo(Sector::class,'resource_id');
    }

    public function location(){
        return $this->belongsTo(Location::class,'resource_id');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'type_map_resource',
                'route_map_id',
                'resource_id',
            ])
            ->useLogName('ResourceMapAssignment Log');
    }
}
