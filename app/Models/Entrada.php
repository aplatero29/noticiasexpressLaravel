<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    /**
     * Get the user record associated with the entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function comentarios()
    {
        return $this->belongsToMany(User::class, 'comentarios', 'entrada_id', 'user_id');
        
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
