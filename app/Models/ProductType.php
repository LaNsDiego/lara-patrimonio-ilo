<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductType extends Model
{
    use HasFactory,SoftDeletes, LogsActivity;

    const CONTAINER = 'CONTENEDORES';
    const VEHICLE = 'VEHICULOS';
    
    protected $fillable = [
        'date',
        'name',
        'description',
        'model',
        'tags',
        'brand_id',
        'measurement_unit_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function product_without_serie()
    {
        return $this->hasOne(Product::class)->whereNull('serial_number');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'date',
                'name',
                'description',
                'model',
                'tags',
                'brand_id',
                'measurement_unit_id',
            ])
            ->useLogName('ProductType Log');
    }
}
