<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RoleHasPermission extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'role_has_permissions';
    protected $fillable = [
        'id',
        'permission_id',
        'role_id',
        'has_access',
    ];

    public function permission(){
        return $this->belongsTo(PermissionSystem::class,'permission_id')->orderBy('system_module_id','asc')->orderBy('action','asc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'permission_id',
                'role_id',
                'has_access',
            ])
            ->useLogName('role_has_permission');
    }
}
