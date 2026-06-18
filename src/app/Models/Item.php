<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'brand_name',
        'description',
        'condition',
        'status',
    ];

    public function categories()
    {
        
        return $this->belongsToMany(Category::class,'item_categories');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

}
