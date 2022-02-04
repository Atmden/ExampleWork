<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'image',
    ];

    public function toArray()
    {
        return
            [
                'id' => $this->id,
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'content' => $this->content,
                'image' => url('/') . $this->image,

            ];
    }
}
