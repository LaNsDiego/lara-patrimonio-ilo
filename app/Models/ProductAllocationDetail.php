<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductAllocationDetail extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['product_allocation_id', 'product_id', 'quantity'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'product_allocation_id',
                'product_id',
                'quantity',
            ])
            ->useLogName('ProductAllocationDetail Log');
    }
}
