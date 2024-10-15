<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Location extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'sector_id',
        'geojson',
        'acronym',
        'location_type_id',
    ];


    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function location_type()
    {
        return $this->belongsTo(LocationType::class,'location_type_id');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'description',
                'sector_id',
                'geojson',
                'acronym',
                'location_type_id',
            ])
            ->useLogName('Location Log');
    }

}
