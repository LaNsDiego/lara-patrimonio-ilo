<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PermissionSystem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'permission_systems';
    protected $fillable = [
        'id',
        'system_module_id',
        'action',
    ];

    public function system_module(){
        return $this->belongsTo(SystemModule::class,'system_module_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'system_module_id',
                'action',
            ])
            ->useLogName('permission_system');
    }
}
