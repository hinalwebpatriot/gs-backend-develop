<?php

namespace lenal\blog\Helpers;

use Illuminate\Http\Response;
use lenal\blog\Models\Article;
use lenal\blog\Models\BlogCategory;
use lenal\blog\Resources\ArticlePreviewResource;
use lenal\blog\Resources\ArticleResource;
use lenal\blog\Resources\BlogCategoryResource;

class Blog
{
    const PERPAGE_DEFAULT = 10;

    public function categories()
    {
        return BlogCategoryResource::collection(BlogCategory::orderBy('priority')->get());
    }

    private function getArticle($slug)
    {
        return Article::whereSlug($slug)->first();
    }
    private function getCategory($slug)
    {
        return BlogCategory::whereSlug($slug)->first();
    }

    public function article($slug)
    {
        /** @var Article $article */
        if (!$article = $this->getArticle($slug)) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }
        // increasing view count
        $article->view_count ++;
        $article->timestamps = false;
        $article->save();
        $article->timestamps = true;

        return new ArticleResource($article);
    }

    public function related($slug) {
        if (!$article = $this->getArticle($slug)) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }
        $articles = Article
            ::where(['category_id' => $article->category_id])
            ->where('id', '!=', $article->id)
            ->orderBy('priority')
            ->take(3)->get();
        return ArticlePreviewResource::collection($articles);
    }

    public function topArticles()
    {
        $topCategories = BlogCategory::orderBy('priority')->has('articles')->take(4)->get();
        if (!$topCategories) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }
        foreach ($topCategories as $topCategory) {
            $topArticles[] = $topCategory->articles->sortBy('priority')->shift();
        }
        $topArticles = collect($topArticles);
        return [
            'main' => new ArticlePreviewResource($topArticles->shift()),
            'list' => ArticlePreviewResource::collection($topArticles)
        ];
    }

    public function list($categorySlug = null)
    {
        if ($categorySlug) {
            $category = $this->getCategory($categorySlug);
            if (!$category) {
                return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
            }
            $articles = Article::where('category_id', $category->id)
                ->orderBy('updated_at', 'desc')
                ->paginate(self::PERPAGE_DEFAULT);
        } else {
            $articles = Article::orderBy('updated_at', 'desc')->paginate(self::PERPAGE_DEFAULT);
        }
        return $articles->items()
            ? ArticlePreviewResource::collection($articles)
            : response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }
}
