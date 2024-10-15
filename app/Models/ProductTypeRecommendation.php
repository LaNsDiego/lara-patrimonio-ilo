<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductTypeRecommendation extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $table = "product_type_recommendations";

    protected $fillable = [
        'name',
    ];
    
    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
            ])
            ->useLogName('ProductTypeRecommendation Log');
    }
}
