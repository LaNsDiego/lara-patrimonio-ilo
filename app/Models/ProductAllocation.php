<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductAllocation extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    
    protected $table = 'product_allocations';
    protected $fillable = [
        'programation_schedule_id',
        'employee_id',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function product_allocation_details(){
        return $this->hasMany(ProductAllocationDetail::class);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'programation_schedule_id',
                'employee_id',
            ])
            ->useLogName('ProductAllocation Log');
    }
}
