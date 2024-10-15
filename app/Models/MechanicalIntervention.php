<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MechanicalIntervention extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    protected $table = 'mechanical_interventions';
    protected $fillable = [
        'id',
        'date',
        'description',
        'cost',
        'status',
        'vehicle_id',
        'responsible_employee_id',
        'created_at',
        'updated_at',
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class, 'id');
    }

    public function responsible(){
        return $this->belongsTo(Employee::class, 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'date',
                'description',
                'cost',
                'status',
                'vehicle_id',
                'responsible_employee_id',
                'created_at',
                'updated_at',
            ])
            ->useLogName('mechanical_intervention');
    }
}
