<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = [
        'faculty_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }
}
