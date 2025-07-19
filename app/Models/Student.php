<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'nim',
        'user_id',
        'department_id',
        'gender',
        'address',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function faculty(): HasOneThrough
    {
        return $this->hasOneThrough(
            Faculty::class,
            Department::class,
            'id',             // Foreign key di Department
            'id',             // Foreign key di Faculty
            'department_id',  // Foreign key di Student
            'faculty_id'      // Foreign key di Department
        );
    }
}
