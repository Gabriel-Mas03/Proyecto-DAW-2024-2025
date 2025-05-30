<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'size',
        'stock',
        'category',
        'URL01',
        'URL02',
        'URL03',
        'URL04',
    ];

    // Relación con el creador del producto (usuario administrador)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con order_items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relación con cart_items
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
