<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
          'user_id' => $this->user_id,
          'comment_id' => $this->comment_id,
          'text' => $this->text,
          'image_path' => $this->image_path,
          'text_file_path' => $this->text_file_path,
          'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
          'user' => new UserResource($this->whenLoaded('user')),
//          'childComments' => CommentResource::collection($this->whenLoaded('childComments')),
//          'parentComment' => new CommentResource($this->whenLoaded('parentComment')),
          'descendants' => $this->whenLoaded('descendants'),
        ];
    }
}
