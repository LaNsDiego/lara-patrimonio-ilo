<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Programation extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $table = "programations";
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'responsible_employee_id',
        'campaing_id',
    ];

    public function responsible_employee()
    {
        return $this->belongsTo(Employee::class, 'responsible_employee_id');
    }

    public function programation_schedules()
    {
        return $this->hasMany(ProgramationSchedule::class)->orderBy('id', 'desc');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'description',
                'start_date',
                'end_date',
                'responsible_employee_id',
                'campaing_id',
            ])
            ->useLogName('Programation Log');
    }
}
