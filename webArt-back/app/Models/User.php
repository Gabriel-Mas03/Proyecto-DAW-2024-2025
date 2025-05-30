<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

     // 🧾 Relación con productos (si es admin o vendedor)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // 🛒 Relación con items del carrito
  
    public function cartItems()
    {
        return $this->hasMany(\App\Models\CartItem::class);
    }
    


    // 📦 Relación con órdenes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
/*
    // 💳 Relación con pagos
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
*/
    public function galleries()
{
    return $this->hasMany(Gallery::class);
}

}
