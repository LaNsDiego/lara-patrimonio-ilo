<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $table = 'suppliers';

    protected $fillable = [
        'type',
        'name',
        'document_type',
        'document_number',
        'phone_number',
        'email',
    ];

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'type',
                'name',
                'document_type',
                'document_number',
                'phone_number',
                'email',
            ])
            ->useLogName('Supplier Log');
    }
}
