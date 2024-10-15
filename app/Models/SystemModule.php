<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemModule extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'system_modules';
    protected $fillable = ['id','name', 'description'];

    public function module_group(){
        return $this->belongsTo(ModuleGroup::class);
    }

    public function module_permissions(){
        return $this->hasMany(PermissionSystem::class);
    }


    public function role_has_permissions(){
        return $this->belongsToMany(RoleHasPermission::class,'role_has_permissions','permission_id','id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'description',
            ])
            ->useLogName('system_module');
    }
}
