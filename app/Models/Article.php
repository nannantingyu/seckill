<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/12
 * Time: 1:45 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ["title", "keywords", "description", "author", "image", "hits", "favor", "disfavor", "publish_time", "recommend", "source_time", "source_url", "source_type"];
    protected $table = "article";

    public function body()
    {
        return $this->hasOne("App\Models\ArticleBody", "id", "id");
    }
}