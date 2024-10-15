<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DetailProductRental extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $fillable = [
        'product_rental_id',
        'work_activity_id',
        'date',
        'start_hour',
        'end_hour',
        'total_hours',
        'total_price',
        'construction_id',
        'location_detail',
    ];

    public function product_rental()
    {
        return $this->belongsTo(ProductRental::class);
    }

    public function work_activity()
    {
        return $this->belongsTo(WorkActivity::class);
    }

    public function construction()
    {
        return $this->belongsTo(Construction::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'product_rental_id',
                'work_activity_id',
                'start_hour',
                'end_hour',
                'total_hours',
                'total_price',
                'construction_id',
                'location_detail',
            ])
            ->useLogName('DetailProductRental Log');
    }
}
