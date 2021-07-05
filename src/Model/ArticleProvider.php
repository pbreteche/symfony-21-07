<?php

namespace App\Model;

class ArticleProvider
{

    /**
     * @return int[]
     */
    public function list(): array
    {
        return [1, 2, 3];
    }

    public function get($id): Article
    {
        $article = new Article();
        $article->id = $id;
        $article->title = 'Titre de l\'article '.$id;

        return $article;
    }
}