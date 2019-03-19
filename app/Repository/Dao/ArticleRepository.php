<?php
namespace App\Repository;
use Illuminate\Support\Facades\DB;

class ArticleRepository{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCateforyAge() {
        return $this->categoryRepository->age;
    }

    /**
     * @param int $categoryId
     * @param int $page
     * @param int $perPage
     * @param string $order [publish_time|hits|favor|disfavor|recommend_publish_time|recommend_hits|recommend_favor|recommend_disfavor]
     * @param bool $withBody
     * @param bool $withDisabled
     * @return array
     */
    public function getPageList($categoryId=0, $page=1, $perPage=10, $order="time", $withBody=false, $withDisabled=false) {
        $articleLists = DB::table('article')
            ->select("article.id", "article.title", "article.keywords", "article.description", "article.image", "article.author", "article.hits", "article.publish_time", "article.favor", "article.disfavor");

        // handle category
        if ($categoryId != 0) {
            $categoryId = $this->categoryRepository->getChildCategories($categoryId);
            $articleLists = $articleLists->join('article_category_map', 'article_category_map.article_id', 'article.id')
                ->whereIn('article_category_map.category_id', $categoryId);
        }

        // handle disabled
        if (!$withDisabled) {
            $articleLists = $articleLists->where('article.state', 1);
        }

        // handle ordering
        if (starts_with('recommend', $order)) {
            $order = substr($order, 10);
            $articleLists = $articleLists->orderBy('article.recommend', 'desc');
        }

        if (in_array($order, ['publish_time', 'hits', 'favor', 'disfavor'])) {
            $articleLists = $articleLists->orderBy('article.'.$order, 'desc');
        }

        // handle body
        if ($withBody) {
            $articleLists = $articleLists->join('article_body', 'article_body.id', 'article.id')
                ->addSelect('article_body.body');
        }

        // handle pagination
        $articleLists = $articleLists->skip(($page-1)*$perPage)->take($perPage)->get()->toArray();
        return $articleLists;
    }
}