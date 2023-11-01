<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\UploadedFile;

class CommentFileService
{
    public static function store(UploadedFile $file, Comment $comment, $file_number): string
    {
        $file_extension = $file->getClientOriginalExtension();
        $file_name = $comment->id . '_file_' . $file_number . '.' . $file_extension;
        $path = $file->storeAs('files', $file_name, 'public');

        return $path;
    }
}
