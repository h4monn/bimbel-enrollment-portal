<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'program_id',
        'name',
        'teacher_name',
        'schedule_days',
        'schedule_time',
        'room',
        'quota',
        'enrolled_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'enrolled_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'class_id');
    }

    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->quota - $this->enrolled_count);
    }
}
