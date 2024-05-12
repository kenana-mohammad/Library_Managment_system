<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends Model
{
    use HasFactory;
    protected $fillable =[
        'name' ,'description','img','quantity','price','genre','status',
    ];

    //many To many
     public function authors()
     {
        return $this->beLongsToMany(Author::class,'book_author')->withPivot(['available']);;
     }
     //morph
     public function reviews(){
        return $this->morphmany(Review::class,'reviewable');
     }
     //many to many
     public function borrow_User()
     {
        return $this->beLongsToMany(User::class,'reservation__pivots')->withPivot(['status','borrow_date','return_date']);
     }
}
