<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = 
    [
        'category_id',
        'name', 
        'description', 
        'price', 
        'stock', 
        'image'
    ];

    // blong to
    public function category ()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
