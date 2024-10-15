<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class AssignmentAsset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'old_responsible_id',
        'new_responsible_id',
    ];

    public function old_responsible()
    {
        return $this->belongsTo(Employee::class, 'old_responsible_id','id');
    }

    public function new_responsible()
    {
        return $this->belongsTo(Employee::class, 'new_responsible_id','id');
    }

    public function details(){
        return $this->hasMany(AssignmentAssetDetail::class);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'old_responsible_id',
                'new_responsible_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->useLogName('Asinacion de Bien Log');
    }
}
