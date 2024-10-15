<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class TransferTicket extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'id',
        'date',
        'start_date',
        'end_date',
        'reason',
        'observation',
        'approver_employee_id',
        'requester_employee_id',
        'origin_establishment_id',
        'destination_establishment_id',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime:Y-m-d',
            'start_date' => 'datetime:Y-m-d',
            'end_date' => 'datetime:Y-m-d',
        ];
    }

    public function approver_employee(){
        return $this->belongsTo(Employee::class, 'approver_employee_id','id');
    }

    public function requester_employee(){
        return $this->belongsTo(Employee::class, 'approver_employee_id','id');
    }

    public function origin_establishment(){
        return $this->belongsTo(Establishment::class, 'origin_establishment_id','id');
    }

    public function destination_establishment(){
        return $this->belongsTo(Establishment::class, 'destination_establishment_id','id');
    }


    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'date',
                'start_date',
                'end_date',
                'reason',
                'observation',
                'approver_employee_id',
                'requester_employee_id',
                'origin_establishment_id',
                'destination_establishment_id',
                'created_at',
                'updated_at',
            ])
            ->useLogName('TransferTicket Log');
    }
}
