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
  ];

  /**
   * @return string
   */
  public function getParentKeyName()
  {
    return 'comment_id';
  }

    public function descendants()
    {
        return $this->hasMany(Comment::class, 'comment_id', 'id')->with(['user','descendants']);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function files()
    {
        return $this->hasMany(CommentFile::class, 'comment_id');
    }

}
