<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

  protected $fillable = [
    'user_id',
    'comment_id',
    'text',
    'image_path',
    'text_file_path',
  ];

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
