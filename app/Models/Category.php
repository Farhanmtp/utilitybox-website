<?php

namespace App\Models;

use App\Traits\UtilsTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use UtilsTrait;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'noindex',
        'nofollow',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

}
