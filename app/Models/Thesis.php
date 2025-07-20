<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thesis extends Model
{
    protected $table = 'theses';

    protected $fillable = [
        'code',
        // 'date',
        'topic_id',
        'student_id',
        'title',
        'action_by',
        'status',
        // 'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $yearMonth = date('Ym');
                $randomStr = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
                $model->code = 'THESIS'. $yearMonth . $randomStr;
            }
        });
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by', 'id');
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ThesisDiscussion::class, 'thesis_id', 'id');
    }
}
