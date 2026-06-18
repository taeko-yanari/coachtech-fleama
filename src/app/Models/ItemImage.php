<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class ItemImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'item_id',
        'image_path',
        'sort_order'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getImageUrlAttribute(): string
{
    if (Str::startsWith($this->image_path, 'http')) {
        return $this->image_path;
    }
    return asset('storage/' . $this->image_path);
}
}
