<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Author extends Model
{
    use HasFactory;
    protected $fillable=[
        'name'
    ];
    //many to many
    public function books()
    {
        return $this->beLongsToMany(Book::class,'book_author')->withPivot(['available']);
    }
    //morph
    public function reviews(){
        return $this->morphmany(Review::class,'reviewable');
     }
}
