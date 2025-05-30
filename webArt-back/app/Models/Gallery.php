<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'url01',
        'url02',
        'url03',
        'url04',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
