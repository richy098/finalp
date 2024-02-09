<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $timestamps = true;
    use HasFactory;
    
    protected $fillable = [
        'project_name',
        'bu_name',
        'start_date',
        'duration',
        'end_date',
        'lead_developer_id',
        'status',
        'development_methodology',
        'system_platform',
        'deployment_type',
        'last_report',
        'description',
    ];

    public function leadDeveloper()
    {
        return $this->belongsTo(User::class, 'lead_developer_id');
    }
    public function developers()
    {
        return $this->belongsToMany(User::class, 'dev_project_relation', 'project_id', 'user_id');
    }
}
