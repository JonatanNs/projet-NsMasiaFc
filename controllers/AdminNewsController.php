<?php

class AdminNewsController extends AbstractController{
    public function adminActualite() : void {
        $secret = $_ENV["SECRET"];
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticle();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("Admin/adminActualite.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'articles' => $articles,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }

    /***************************** Admin Actuality ***************************/
    public function checkAddArticle(): void {
        $secret = $_ENV["SECRET"];
    
       // Verification of required fields
        if (
            isset($_POST["titleArticle"]) && 
            isset($_POST["excerptArticle"]) &&
            isset($_POST["contentArticle"]) &&
            isset($_POST["imgAltArticle"])
        ) {
            $tokenManager = new CSRFTokenManager(); 
    
            // CSRF token verification
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                
                $media = '';
    
                // Checking and processing the uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/img/uploadsNews/";
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
    
                    $originalFileName = basename($_FILES["addMediaFile"]["name"]);
                    $sanitizedFileName = str_replace(' ', '_', $originalFileName);
                    $targetFile = $targetDir . $sanitizedFileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
                    // Image file validation
                    $check = getimagesize($_FILES["addMediaFile"]["tmp_name"]);
                    if ($check === false) {
                        $_SESSION["error"] = "Le fichier n’est pas une image.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location: index.php?route=adminActualite&secret=$secret");
                            exit;
                        }
                    }
    
                    $media = 'assets/img/uploadsNews/' . htmlspecialchars($sanitizedFileName);
                    var_dump($media);
                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location: index.php?route=adminActualite&secret=$secret");
                    exit;
                }
    
                // Article Data Processing
                $title = htmlspecialchars_decode($_POST["titleArticle"]);
                $excerpt = htmlspecialchars_decode($_POST["excerptArticle"]);
                $content = nl2br(htmlspecialchars_decode($_POST["contentArticle"]));
                $imgAlt = htmlspecialchars($_POST["imgAltArticle"]);
    
                $date = new DateTime();
                $formattedDate = $date->format('Y-m-d');
    
                // Creation of the article
                $articleManager = new ArticleManager();
                $newArticle = new Article(
                    $title, 
                    $excerpt, 
                    $content, 
                    $formattedDate, 
                    $media, 
                    $imgAlt
                );
                $articleManager->createArticle($newArticle);
    
                $_SESSION["valide"] = "Nouvel article ajouté.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;
    
            } else {
                $_SESSION["error"] = "Une erreur de validation est survenue.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;
            }
    
        } else {
            $_SESSION["error"] = "Tous les champs requis ne sont pas remplis.";
            header("Location: index.php?route=adminActualite&secret=$secret");
            exit;
        }
    }
    

    public function checkChangeTitleArticle() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["changeTitleArticle"]) && isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $title = htmlspecialchars_decode($_POST["changeTitleArticle"]);

                $articleManager = new ArticleManager();

                $article = $articleManager->getAllArticleById($_POST["articleId"]);

                $articleManager->changeArticle( 
                                                $_POST["articleId"],
                                                $title, 
                                                $article->getExcerpt(), 
                                                $article->getContent(), 
                                                $article->getDate(), 
                                                $article->getImgUrl(), 
                                                $article->getImgAlt()
                                            );
                $newArticle = new Article(
                                $title, 
                                $article->getExcerpt(), 
                                $article->getContent(), 
                                $article->getDate(), 
                                $article->getImgUrl(), 
                                $article->getImgAlt()
                        );
                $newArticle->setId($_POST["articleId"]);


                $_SESSION["valide"] = "Article modifier.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue1.";
            header("Location: index.php?route=adminActualite&secret=$secret");
            exit;
        } 
    }

    public function checkChangeContentArticle() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["changeExcerptArticle"]) && isset($_POST["changeContentArticle"]) && isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $excerpt = htmlspecialchars_decode($_POST["changeExcerptArticle"]);
                $content = nl2br(htmlspecialchars_decode($_POST["changeContentArticle"]));

                $articleManager = new ArticleManager();

                $article = $articleManager->getAllArticleById($_POST["articleId"]);

                $articleManager->changeContentArticle( 
                                                $_POST["articleId"],
                                                $excerpt, 
                                                $content
                                            );
                $newArticle = new Article(
                                $article->getTitle(),
                                $excerpt, 
                                $content, 
                                $article->getDate(), 
                                $article->getImgUrl(), 
                                $article->getImgAlt()
                        );
                $newArticle->setId($_POST["articleId"]);

                $_SESSION["valide"] = "Article modifier.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue1.";
            header("Location: index.php?route=adminActualite&secret=$secret");
            exit;
        } 
    }

    public function checkChangeImgArticle() : void {
        var_dump($_POST);
        var_dump($_FILES);
        $secret = $_ENV["SECRET"];
        if(isset($_POST["changeImgAltArticle"]) && isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $imgAlt = htmlspecialchars_decode($_POST["changeImgAltArticle"]);

                $articleManager = new ArticleManager();
                $article = $articleManager->getAllArticleById($_POST["articleId"]);

                // Check if a new image was uploaded
                if (isset($_FILES["addMediaFile"]["tmp_name"]) && !empty($_FILES["addMediaFile"]["tmp_name"])) {
                    $targetDir = "assets/img/uploadsNews/";
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    
                    // Replace spaces with underscores in the file name
                    $originalFileName = basename($_FILES["addMediaFile"]["name"]);
                    $sanitizedFileName = str_replace(' ', '_', $originalFileName);
                    $targetFile = $targetDir . $sanitizedFileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
                    // Validate image file
                    $check = getimagesize($_FILES["addMediaFile"]["tmp_name"]);
                    if ($check === false) {
                        $_SESSION["error"] = "Le fichier n’est pas une image.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Validate file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Move uploaded file
                    if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                        $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // New image path
                    $media = 'assets/img/uploadsNews/' . htmlspecialchars($sanitizedFileName);
                    
                    // Remove the old image file if it exists
                    $filePath = $article->getImgUrl();
                    if (!empty($filePath) && file_exists($filePath) && !unlink($filePath)) {
                        $_SESSION["error"] = "Une erreur est survenue lors de la suppression de l'ancien fichier.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
                }

                $articleManager->changeImgArticle( 
                                                $_POST["articleId"],
                                                $media,
                                                $imgAlt
                                            );
                $newArticle = new Article(
                                $article->getTitle(),
                                $article->getExcerpt(), 
                                $article->getContent(), 
                                $article->getDate(), 
                                $media,
                                $imgAlt
                        );
                $newArticle->setId($_POST["articleId"]);

                $_SESSION["valide"] = "Article modifier.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue1.";
            header("Location: index.php?route=adminActualite&secret=$secret");
            exit;
        }
    }

    public function checkRemoveArticle() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){

                $articleManager = new ArticleManager();
                $articleManager->removeArticle($_POST["articleId"]);

                $_SESSION["valide"] = "Article retirer.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminActualite&secret=$secret");
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: index.php?route=adminActualite&secret=$secret");
            exit;
        }
    }
}