<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder',
        'pictureName'
    ];
}
