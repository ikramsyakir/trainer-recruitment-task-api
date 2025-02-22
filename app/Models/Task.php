<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    const PENDING = 'pending';

    const IN_PROGRESS = 'in_progress';

    const COMPLETED = 'completed';

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
    ];
}
