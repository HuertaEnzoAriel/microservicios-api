<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'weight',
        'stock',
        'is_active',
        'category_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

// Método para calcular rating promedio
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    // Método para contar reseñas
    public function reviewsCount()
    {
        return $this->reviews()->count();
    }

    public function __toString()
    {
        return strtoupper($this->name) ." $$this->price";
    }
}