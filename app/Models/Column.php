<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $guarded = [];

    public function cards()
    {
        // Ambil kartu, urutkan berdasarkan posisi
        return $this->hasMany(Card::class)->orderBy('position');
    }
}