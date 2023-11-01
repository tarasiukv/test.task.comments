<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_path' => $this->file_path,
            'comment_id' => $this->comment_id,
            'comment' => new CommentResource($this->whenLoaded('comment')),
            'created_at' => $this->created_at ? $this->created_at->format('d.m.Y, H:i') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d.m.Y, H:i') : null,
        ];
    }
}

