<?php

namespace lenal\blog\Observers;

use lenal\blog\Models\Article;

/**
 * Class ArticleObserver
 *
 * @package lenal\blog\Observers
 */
class ArticleObserver
{
    /**
     * @param Article $article
     */
    public function deleting(Article $article)
    {
        $article->unsearchable();
    }
}
