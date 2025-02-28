<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
  use HasFactory;
  protected $table = 'posts';
  protected $guarded = ['id'];
  protected $casts = [
    'tags' => 'array'
  ];

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'post_user')->withPivot(['order'])->withTimestamps();
  }

  public function comments(): MorphMany
  {
    return $this->morphMany(Comment::class, 'commentable');
  }
}
