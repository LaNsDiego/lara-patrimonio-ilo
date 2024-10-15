<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Incident extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected $table = 'incidents';
    protected $fillable = [
        'id',
        'time',
        'wanted_person_id',
        'staff_security_id',
        'vehicle_plate',
        'code',
        'sector_id',
        'vehicle_id',
        'reason',
        'action_to_take',
    ];

    public function photos(){
        return $this->hasMany(IncidentPhoto::class);
    }

    public function staff_security(){
        return $this->belongsTo(Employee::class);
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function vehicle(){
        return $this->belongsTo(Product::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'wanted_person_id',
                'description',
                'time',
            ])
            ->useLogName('Incident');
    }
}
