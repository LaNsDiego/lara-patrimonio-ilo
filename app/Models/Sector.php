<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sector extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $fillable = [
        'name',
        'sector_type_id',
        'description',
        'geojson',
        'parent_sector_id',
    ];

    public function sector_type(){
        return $this->belongsTo(SectorType::class,'sector_type_id');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'sector_type_id',
                'description',
                'geojson',
                'parent_sector_id',
            ])
            ->useLogName('Sector Log');
    }
}
