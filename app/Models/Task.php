<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'estimated_time',
        'project_id',
        'user_id',
        'status',
        'due_date',
    ];

    protected $casts = [
        'estimated_time' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-secondary">في الانتظار</span>',
            'in_progress' => '<span class="badge bg-primary">قيد التنفيذ</span>',
            'completed' => '<span class="badge bg-success">مكتملة</span>',
            'cancelled' => '<span class="badge bg-danger">ملغاة</span>',
            default => '<span class="badge bg-secondary">غير محدد</span>',
        };
    }
}
