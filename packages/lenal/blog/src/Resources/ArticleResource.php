<?php

namespace lenal\blog\Resources;

class ArticleResource extends ArticlePreviewResource {

    public function toArray($request)
    {
        $article = parent::toArray($request);
        $article['content'] = ArticleDetailBlockResource::collection($this->detailText);
        return $article;
    }
}