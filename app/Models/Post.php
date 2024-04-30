<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
