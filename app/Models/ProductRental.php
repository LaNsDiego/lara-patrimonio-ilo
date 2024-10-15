<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductRental extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $fillable = [
        'code',
        'product_id',
        'price_per_hour',
        'employee_id',
        'establishment_id',
        'start_date',
        'end_date',
        'total_hours',
        'total_price',
        'is_external',
        'number_dni',
        'full_name',
        'mileage_traveled',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function detail_product_rentals()
    {
        return $this->hasMany(DetailProductRental::class);
    }

    public function assigned_product_rentals()
    {
        return $this->hasMany(AssignedProductRental::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'code',
                'product_id',
                'price_per_hour',
                'employee_id',
                'establishment_id',
                'start_date',
                'end_date',
                'total_hours',
                'total_price',
                'is_external',
                'number_dni',
                'full_name',
                'mileage_traveled',
                'status',
            ])
            ->useLogName('ProductRental Log');
    }

}
