<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SectorType extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $table = "sector_types";
    protected $fillable = [
        'name',
        'color',
    ];

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'color',
            ])
            ->useLogName('SectorType Log');
    }
}
