<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisDiscussion extends Model
{
    protected $table = 'thesis_discussions';

    protected $fillable = [
        'thesis_id',
        'user_id',
        'message',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class, 'thesis_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
