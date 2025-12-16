<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    // KITA GUNAKAN FILLABLE AGAR LEBIH SPESIFIK & AMAN
    protected $fillable = [
        'column_id',
        'title',
        'position',
        'image_path',
        'description'
    ];

    // Relasi ke Column
    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}