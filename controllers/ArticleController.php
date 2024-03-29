<?php

class ArticleController extends AbstractController
{
    public function news()
    {
        $articleManager = new ArticleManager();

        $articles = $articleManager->getAllArticle();

        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $this->render("news.html.twig", [
            'articles' => $articles,
            'userIsConect' => $userIsConect,
            
        ]);
    }

    public function homeArticle($id)
    {
        $articleManager = new ArticleManager();

        $articles = $articleManager->getAllArticleById($id);

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $adminRnd7sX23 =  isset($_ENV['ConnexionAdmin_35as3ENm7LV3nz3Nej4R']) ? $_ENV['ConnexionAdmin_35as3ENm7LV3nz3Nej4R'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $this->render("homeArticle.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'adminRnd7sX23' => $adminRnd7sX23,
            'articles' => $articles
        ]);
    }

    public function club()
    {
        

        $this->render("club.html.twig", [
            
        ]);
    }
}