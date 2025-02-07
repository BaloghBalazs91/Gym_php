<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{
    use HasFactory;
    protected $fillable = [
        'start',
        'duration',
        'trainer_id',
        'room_id',
        'capacity',
        'training_method_id'
    ];
    protected $hidden = [
    ];
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'start' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    public function trainingMethod():BelongsTo
    {
        return $this->belongsTo(TrainingMethod::class);
    }

    public function room() :BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    # A training belongs to one user (trainer).
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function trainees() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'training_trainee',
            'training_id',
            'trainee_id'
        );
    }
}
