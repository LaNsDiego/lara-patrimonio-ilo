<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'employees';
    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'job_title_id',
        'establishment_id',
        'email',
        'phone_number',
    ];

    public function job_title()
    {
        return $this->belongsTo(JobTitle::class);
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
                'document_type',
                'document_number',
                'name',
                'job_title_id',
                'establishment_id',
                'email',
                'phone_number',
            ])
            ->useLogName('Employee Log');
    }
}
