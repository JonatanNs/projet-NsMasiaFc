<?php

class ArticleManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/
    public function createArticle( Article $article ) : void {
        try{                        
            $query = $this->db->prepare("INSERT INTO articles (id, title, excerpt, content, date, img_url, img_alt) 
            VALUES (null, :title, :excerpt, :content, :date, :img_url, :img_alt)");
            $parameters = [
                'title' =>  $article->getTitle(), 
                'excerpt' => $article->getExcerpt(), 
                'content' => $article->getContent(), 
                'date' => $article->getDate(), 
                'img_url' => $article->getImgUrl(),  
                'img_alt' => $article->getImgAlt()
            ];
            
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to create article.");
        }  
    }
    
    /**********************************************************
                             * CHANGE *
    **********************************************************/
    public function changeArticle(
                                    int $id, 
                                    string $title, 
                                    string $excerpt, 
                                    string $content, 
                                    string $date, 
                                    string $img_url, 
                                    string $img_alt
                                ) : void {
        try{
            $query = $this->db->prepare("UPDATE articles 
                                        SET title = :title, excerpt = :excerpt, content = :content, date = :date, img_url = :img_url, img_alt = :img_alt 
                                        WHERE id = :id");
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
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change article.");
        } 
    }

    public function changeTitleArticle(int $id, string $title) : void {
        try{
            $query = $this->db->prepare("UPDATE articles 
                                        SET title = :title 
                                        WHERE id = :id");
            
            $parameters = [
                'id' => $id,
                'title' =>  $title
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change title article.");
        }
    }

    public function changeContentArticle(
                                            int $id, 
                                            string $excerpt, 
                                            string $content
                                        ) : void {
        try{
            $query = $this->db->prepare("UPDATE articles 
                                        SET excerpt = :excerpt, content = :content 
                                        WHERE id = :id");
        
            $parameters = [
                'id' => $id,
                'excerpt' => $excerpt, 
                'content' => $content
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change article.");
        }
    }

    public function changeDateArticle(int $id, string $date) : void {
        try{
            $query = $this->db->prepare("UPDATE articles 
                                        SET date = :date 
                                        WHERE id = :id");
        
            $parameters = [
                'id' => $id,
                'date' =>  $date
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change article.");
        }
    }

    public function changeImgArticle(
                                        int $id, 
                                        string $img_url, 
                                        string $img_alt
                                    ) : void {
        try{
            $query = $this->db->prepare("UPDATE articles 
                                        SET img_url = :img_url, img_alt = :img_alt 
                                        WHERE id = :id");
        
            $parameters = [
                'id' => $id, 
                'img_url' => $img_url, 
                'img_alt' => $img_alt
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change image for article.");
        }
    }
    

    /**********************************************************
                             * REMOVE *
    **********************************************************/
    public function removeArticle(int $id) : void {
        try{
            $query = $this->db->prepare("DELETE FROM articles WHERE id = :id");
            $parameters = [
                'id' =>  $id, 
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to remove article.");
        } 
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllArticle() : array {
        try{
            $query = $this->db->prepare("SELECT * FROM articles WHERE date <= CURRENT_DATE() ORDER BY date DESC");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $articles = [];
            foreach($result as $item){
                $article = new Article(
                                        $item["title"], 
                                        $item["excerpt"], 
                                        $item["content"], 
                                        $item["date"], 
                                        $item["img_url"], 
                                        $item["img_alt"]
                                    );
                $article->setId($item["id"]);
                $articles[] = $item;
            }
            return $articles ;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all articles.");
        }
    }

    public function getAllArticleById(int $id) : ? Article {
        try{
            $query = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if($result){
                $article = new Article(
                                        $result["title"], 
                                        $result["excerpt"], 
                                        $result["content"], 
                                        $result["date"], 
                                        $result["img_url"], 
                                        $result["img_alt"]
                                    );
                $article->setId($result["id"]);
                return $article ;
            }
            return null; 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all article by id.");
        }
    }
}