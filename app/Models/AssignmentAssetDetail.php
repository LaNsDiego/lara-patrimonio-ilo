<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class AssignmentAssetDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'assignment_asset_id',
        'asset_id',
    ];

    public function assignment_asset()
    {
        return $this->belongsTo(AssignmentAsset::class, 'assignment_asset_id','id');
    }

    public function asset()
    {
        return $this->belongsTo(Product::class, 'asset_id','id');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'assignment_asset_id',
                'asset_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->useLogName('Detalle asinacion de bien Log');
    }
}
