<?php

class ArticleController extends AbstractController
{
    public function news() : void
    {
        $articleManager = new ArticleManager();

        $articles = $articleManager->getAllArticle();

        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        
        $this->render("news.html.twig", [
            'articles' => $articles,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'userIsConect' => $userIsConect,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function homeArticle($id) : void
    {
        $articleManager = new ArticleManager();

        $articles = $articleManager->getAllArticleById($id);

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $secret = $_ENV["SECRET"];

        $this->render("homeArticle.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'articles' => $articles,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }
}