<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Construction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'establishment_id',
        'location_id',
        'resident_id',
        'mount',
        'start_date',
        'end_date',
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function resident()
    {
        return $this->belongsTo(Employee::class, 'resident_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'establishment_id',
                'location_id',
                'resident_id',
                'mount',
                'start_date',
                'end_date',
            ])
            ->useLogName('Construction Log');
    }
}
