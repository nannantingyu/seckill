<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ArticleBody extends Model {
    protected $table = "article_body";
    protected $fillable = ["id", "body"];
}