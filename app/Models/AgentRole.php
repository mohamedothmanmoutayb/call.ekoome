<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentRole extends Model
{
    use HasFactory;
    
    public function users()
    {
        return $this->hasMany('App\Models\User','id_role','id');
    }
    
    public function roleagent()
    {
        return $this->hasMany('App\Models\User','id_agentrole','id');
    }

    public function permission()
    {
        return $this->hasMany('App\Models\RoleHasPermission','role_id','id');
    }
}
