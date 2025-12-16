<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $guarded = []; // Agar bisa mass input

    public function columns()
    {
        // Ambil kolom, urutkan berdasarkan posisi
        return $this->hasMany(Column::class)->orderBy('position');
    }
}