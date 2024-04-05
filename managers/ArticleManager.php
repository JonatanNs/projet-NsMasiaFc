<?php

class ArticleManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/
    public function createArticle(string $title, string $excerpt, string $content, string $date, string $img_url, string $img_alt) : void {
        $query = $this->db->prepare("INSERT INTO articles (id, title, excerpt, content, date, img_url, img_alt) 
        VALUES (null, :title, :excerpt, :content, :date, :img_url, :img_alt)");
        $parameters = [
            'title' =>  $title, 
            'excerpt' => $excerpt, 
            'content' => $content, 
            'date' => $date, 
            'img_url' => $img_url, 
            'img_alt' => $img_alt
        ];
        $query->execute($parameters); 
    }
    
    /**********************************************************
                             * CHANGE *
    **********************************************************/
    public function changeArticle(int $id, string $title, string $excerpt, string $content, string $date, string $img_url, string $img_alt) : void {
        $query = $this->db->prepare("UPDATE articles SET title = :title, excerpt = :excerpt, content = :content, date = :date, img_url = :img_url, :img_alt WHERE id = :id");

        $parameters = [
            'id' => $id,
            'title' =>  $title, 
            'excerpt' => $excerpt, 
            'content' => $content, 
            'date' =>  $date, 
            'img_url' => $img_url, 
            'img_alt' => $img_alt
        ];
        $query->execute($parameters); 
    }

    /**********************************************************
                             * REMOVE *
    **********************************************************/
    public function removeArticle(int $id) : void {
        $query = $this->db->prepare("DELETE FROM articles WHERE id = :id");
        $parameters = [
            'id' =>  $id, 
        ];
        $query->execute($parameters); 
    }

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