<?php

class ArticleManager extends AbstractManager{

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllArticle() : array {
        $query = $this->db->prepare("SELECT * FROM articles");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach($result as $item){
            $article = new Article($item["title"], $item["excerpt"], $item["content"], $item["date"], $item["img_url"], $item["img_alt"]);
            $article->setId($item["id"]);
            $articles[] = $item;
        }
        return $articles ;
    }

    public function getAllArticleById(int $id) : ? Article {
        $query = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result){
            $article = new Article($result["title"], $result["excerpt"], $result["content"], $result["date"], $result["img_url"], $result["img_alt"]);
            $article->setId($result["id"]);
            return $article ;
        }
        return null; 
    }
}