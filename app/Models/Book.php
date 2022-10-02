<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function library()
    {
        return $this->belongsToMany(Library::class, 'library_books')->withPivot('stock');
    }
}
