<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function articleLikes()
    {
        return $this->hasMany(UserLikeArticle::class,'article_id','id');
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class,'article_id','id');
    }


}
