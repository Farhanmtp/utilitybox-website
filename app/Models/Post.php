<?php

namespace App\Models;

use App\Models\Traits\ActiveTrait;
use App\Traits\UtilsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, ActiveTrait, UtilsTrait;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'description',
        'content',
        'image',
        'banner',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'noindex',
        'nofollow',
        'status',
    ];

    protected $appends = ['url', 'image_url', 'banner_url'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getUrlAttribute()
    {
        return $this->slug ? route('blogDetail', $this->slug) : '';
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? url($this->image) : '';
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? url($this->banner) : '';
    }
}
