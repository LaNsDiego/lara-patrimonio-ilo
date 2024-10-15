<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AssignedProductRental extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'assigned_product_rental';
    protected $fillable = [
        'product_rental_id',
        'detail_kardex_id',
    ];

    public function product_rental()
    {
        return $this->belongsTo(ProductRental::class);
    }

    public function detail_kardex()
    {
        return $this->belongsTo(DetailKardex::class);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'product_rental_id',
                'detail_kardex_id',
            ])
            ->useLogName('AssignedProductRental Log');
    }
}
