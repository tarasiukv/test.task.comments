<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class CommentFile extends Model
{
  use HasFactory, HasRecursiveRelationships;

  protected $fillable = [
      'file_path',
      'comment_id',
  ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
