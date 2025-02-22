<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property Carbon $due_date
 * @property User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description ?? '-',
            'status' => $this->status,
            'due_date' => $this->due_date ? $this->due_date->format('d-m-Y') : '-',
            'owner_name' => $this->user->name ?? '-',
            'created_at' => $this->created_at->format('d-m-Y h:i A'),
            'updated_at' => $this->updated_at->format('d-m-Y h:i A'),
        ];
    }
}
