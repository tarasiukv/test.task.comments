<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Comment extends Model
{
  use HasFactory, HasRecursiveRelationships;

  protected $fillable = [
    'user_id',
    'comment_id',
    'text',
    'image_path',
    'text_file_path',
  ];

  /**
   * @return string
   */
  public function getParentKeyName()
  {
    return 'comment_id';
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function parentComment()
  {
    return $this->belongsTo(Comment::class, 'comment_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function childComments()
  {
    return $this->hasMany(Comment::class, 'comment_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }


}
