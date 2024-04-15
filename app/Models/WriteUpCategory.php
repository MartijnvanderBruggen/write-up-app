<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriteUpCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function writeups(): BelongsToMany
    {
        return $this->belongsToMany(WriteUp::class);
    }
}
