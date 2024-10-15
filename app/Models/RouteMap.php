<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RouteMap extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    
    protected $table = "route_maps";
    protected $fillable = [
        'id',
        'name',
        'point_center',
    ];

    public function road_ways(){
        return $this->hasMany(ResourceMapAssignment::class,'route_map_id')
            ->where('type_map_resource',ResourceMapAssignment::MAP_RESOURCE_ROAD);
    }

    public function sectors(){
        return $this->hasMany(ResourceMapAssignment::class,'route_map_id')
            ->where('type_map_resource',ResourceMapAssignment::MAP_RESOURCE_SECTOR);
    }

    public function locations(){
        return $this->hasMany(ResourceMapAssignment::class,'route_map_id')
            ->where('type_map_resource',ResourceMapAssignment::MAP_RESOURCE_LOCATION);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'point_center',
            ])
            ->useLogName('RouteMap Log');
    }
}
