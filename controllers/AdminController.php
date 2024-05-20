<?php

class AdminController extends AbstractController{

    public function checkAdmin(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();
            
        $rivalTeamManager = new RivalTeamManager();
        $nsMasiaManager = new NsMasiaManager();
    
        $this->render("Admin/admin.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminHome(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $matchManager = new MatchManager();
        $matchPlay = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();


        $this->render("Admin/adminHome.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'matchPlays' => $matchPlay,
            'resultMatchs' => $resultMatchs,
        ]);
    }

    public function adminClub(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $nsMasiaManager = new NsMasiaManager();
        $players = $nsMasiaManager->getPlayerNsMasia();

        $this->render("Admin/adminClub.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'players' => $players
        ]);
    }

    public function adminActualite(){
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

        $this->render("Admin/adminActualite.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'articles' => $articles
        ]);
    }

    public function adminBoutique(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $this->render("Admin/adminBoutique.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminBiletterie(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $this->render("Admin/adminBiletterie.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }
    /***************************** Admin Home ***************************/
    public function checkAddResult(){
        if(isset($_POST["resultTeamNsMasia"]) && 
            isset($_POST["resultTeamRival"]) && 
            isset($_POST["matchId"])
        ){

            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $matchManager = new MatchManager();

                $match = $matchManager->getMatchsByIdPlay($_POST["matchId"]);
                $score_nsMasia = htmlspecialchars($_POST["resultTeamNsMasia"]);
                $score_rivalTeam = htmlspecialchars($_POST["resultTeamRival"]);

                $matchManager->addResulteMatch($match, $_POST["matchId"], $score_nsMasia, $score_rivalTeam);
                
                $_SESSION["valide"] = "Resultat match Ajouter.";
                header('Location: index.php?route=checkAdmin');
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=checkAdmin');
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header('Location: index.php?route=checkAdmin');
            exit;
        }
    }

    public function checkChangeResult(){
        
    }

    public function checkRemoveResult(){
        
    }

    /***************************** Admin Billeterie ***************************/
    public function checkAddMatchs(){
        if(isset($_POST["location"]) && isset($_POST["rivalTeam"]) && isset($_POST["date"]) && isset($_POST["time"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $location = htmlspecialchars($_POST["location"]);
                $rivalTeam = htmlspecialchars($_POST["rivalTeam"]);
                $heures_input = htmlspecialchars($_POST["time"]);
                $date_input = htmlspecialchars($_POST["date"]);
                //mettre la date format sql
                $date = new DateTime($date_input);
                $formatted_date = $date->format('Y-m-d');
                
                $matchManager = new MatchManager();
                $nsManager = new NsMasiaManager();
                $rivalTeamManager = new RivalTeamManager();

                $nsMasia = $nsManager->getNsMasia();
                $team = $rivalTeamManager->getAllRivalTeamsByName($rivalTeam);
                $matchManager->createMatch($nsMasia, $team->getId(), $location, $heures_input, $formatted_date);

                $_SESSION["valide"] = "Nouveau match Ajouter.";
                header('Location: index.php?route=checkAdmin');
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=checkAdmin');
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header('Location: index.php?route=checkAdmin');
            exit;
        } 
    }

    public function checkChangeMatch(){
        
    }

    public function checkRemoveMatch(){
        
    }

    /***************************** Admin Actuality ***************************/
    public function checkAddArticle(){

        if(isset($_POST["titleArticle"]) && 
        isset($_POST["excerptArticle"]) &&
        isset($_POST["contentArticle"]) &&
        isset($_POST["dateArticle"]) &&
        isset($_POST["imgUrlArticle"]) &&
        isset($_POST["imgAltArticle"]) 
        ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $title = htmlspecialchars($_POST["titleArticle"]);
                $excerpt = htmlspecialchars($_POST["excerptArticle"]);
                $content = htmlspecialchars($_POST["contentArticle"]);
                $date = htmlspecialchars($_POST["dateArticle"]);
                $imgUrl = htmlspecialchars($_POST["imgUrlArticle"]);
                $imgAlt = htmlspecialchars($_POST["imgAltArticle"]);

                $articleManager = new ArticleManager();
                $articleManager->createArticle( 
                                                $title, 
                                                $excerpt, 
                                                $content, 
                                                $date, 
                                                $imgUrl, 
                                                $imgAlt
                                            );
                $newArticle = new Article(
                                            $title, 
                                            $excerpt, 
                                            $content, 
                                            $date, 
                                            $imgUrl, 
                                            $imgAlt
                                        );

                $_SESSION["valide"] = "Nouvelle article ajouter.";
                header('Location: index.php?route=adminActualite');
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=adminActualite');
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header('Location: index.php?route=adminActualite');
            exit;
        } 
    }

    public function checkChangeAllArticle(){
        if(isset($_POST["changeTitleArticle"]) && 
        isset($_POST["changeExcerptArticle"]) &&
        isset($_POST["changeContentArticle"]) &&
        isset($_POST["changeDateArticle"]) &&
        isset($_POST["changeImgUrlArticle"]) &&
        isset($_POST["changeImgAltArticle"]) &&
        isset($_POST["articleId"])  
        ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $title = htmlspecialchars($_POST["changeTitleArticle"]);
                $excerpt = htmlspecialchars($_POST["changeExcerptArticle"]);
                $content = htmlspecialchars($_POST["changeContentArticle"]);
                $date = htmlspecialchars($_POST["changeDateArticle"]);
                $imgUrl = htmlspecialchars($_POST["changeImgUrlArticle"]);
                $imgAlt = htmlspecialchars($_POST["changeImgAltArticle"]);

                $articleManager = new ArticleManager();
                $articleManager->changeArticle( 
                                                $_POST["articleId"],
                                                $title, 
                                                $excerpt, 
                                                $content, 
                                                $date, 
                                                $imgUrl, 
                                                $imgAlt
                                            );
                $newArticle = new Article(
                                            $title, 
                                            $excerpt, 
                                            $content, 
                                            $date, 
                                            $imgUrl, 
                                            $imgAlt
                                        );

                $newArticle->setId($_POST["articleId"]);

                $_SESSION["valide"] = "Article modifier.";
                header('Location: index.php?route=adminActualite');
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=adminActualite');
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header('Location: index.php?route=adminActualite');
            exit;
        } 
    }

    public function checkChangeTitleArticle(){
        if(isset($_POST["changeTitleArticle"]) && isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $title = htmlspecialchars($_POST["changeTitleArticle"]);

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
                header('Location: index.php?route=adminActualite');
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=adminActualite');
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue1.";
            header('Location: index.php?route=adminActualite');
            exit;
        } 
    }

    public function checkChangeContentArticle(){
        if(isset($_POST["changeExcerptArticle"]) && isset($_POST["changeContentArticle"]) && isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $excerpt = htmlspecialchars($_POST["changeExcerptArticle"]);
                $content = htmlspecialchars($_POST["changeContentArticle"]);

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
                header('Location: index.php?route=adminActualite');
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=adminActualite');
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue1.";
            header('Location: index.php?route=adminActualite');
            exit;
        } 
    }

    public function checkChangeImgArticle(){
        if(isset($_POST["changeImgUrlArticle"]) && isset($_POST["changeImgAltArticle"]) && isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $imgUrl = htmlspecialchars($_POST["changeImgUrlArticle"]);
                $imgAlt = htmlspecialchars($_POST["changeImgAltArticle"]);

                $articleManager = new ArticleManager();

                $article = $articleManager->getAllArticleById($_POST["articleId"]);

                $articleManager->changeImgArticle( 
                                                $_POST["articleId"],
                                                $imgUrl,
                                                $imgAlt
                                            );
                $newArticle = new Article(
                                $article->getTitle(),
                                $article->getExcerpt(), 
                                $article->getContent(), 
                                $article->getDate(), 
                                $imgUrl,
                                $imgAlt
                        );
                $newArticle->setId($_POST["articleId"]);

                $_SESSION["valide"] = "Article modifier.";
                header('Location: index.php?route=adminActualite');
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=adminActualite');
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue1.";
            header('Location: index.php?route=adminActualite');
            exit;
        }
    }

    public function checkRemoveArticle(){
        if(isset($_POST["articleId"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){

                $articleManager = new ArticleManager();
                $articleManager->removeArticle($_POST["articleId"]);

                $_SESSION["valide"] = "Article retirer.";
                header('Location: index.php?route=adminActualite');
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=adminActualite');
                exit;
            } 
            
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header('Location: index.php?route=adminActualite');
            exit;
        }
    }

    /***************************** Admin Club ***************************/
    public function checkAddPlayer() {
        if (
            isset($_POST["addFirst_name"]) && 
            isset($_POST["addLast_name"]) &&
            isset($_POST["addNameJersay"]) && 
            isset($_POST["addNumberJersay"]) &&
            isset($_POST["addPoste"])
        ) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $firstName = htmlspecialchars($_POST["addFirst_name"]);
                $lastName = htmlspecialchars($_POST["addLast_name"]);
                $nameJersay = htmlspecialchars($_POST["addNameJersay"]);
                $number = htmlspecialchars($_POST["addNumberJersay"]);
                $position = htmlspecialchars($_POST["addPoste"]);
    
                $nsmasiaManager = new NsMasiaManager();
    
                if ($nsmasiaManager->isJerseyNumberExists($number)) {
                    $_SESSION["error"] = "Un joueur a déjà ce numéro de maillot.";
                } else {
                    $nsmasiaManager->createPlayer($firstName, $lastName, $nameJersay, $number, $position);
                    $_SESSION["valide"] = "Joueur ajouté.";
                }
            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
        }
    
        header('Location: index.php?route=adminClub');
        exit;
    }
    
    
    public function checkChangePlayer(){
        if (
            isset($_POST["changeFirst_name"]) && 
            isset($_POST["changeLast_name"]) &&
            isset($_POST["changeNameJersay"]) && 
            isset($_POST["changeNumberJersay"]) &&
            isset($_POST["changePoste"]) && 
            isset($_POST["playerId"])
        ) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $firstName = htmlspecialchars($_POST["changeFirst_name"]);
                $lastName = htmlspecialchars($_POST["changeLast_name"]);
                $nameJersay = htmlspecialchars($_POST["changeNameJersay"]);
                $number = htmlspecialchars($_POST["changeNumberJersay"]);
                $position = htmlspecialchars($_POST["changePoste"]);
    
                $nsmasiaManager = new NsMasiaManager();
    
                if ($nsmasiaManager->isJerseyNumberExists($number)) {
                    $_SESSION["error"] = "Un joueur a déjà ce numéro de maillot.";
                } else {

                    $nsmasiaManager->changePlayer(
                                                    $_POST["playerId"],
                                                    $firstName, 
                                                    $lastName, 
                                                    $nameJersay, 
                                                    $number, 
                                                    $position
                                                );

                    $newPlayer = new PlayerNsMasia(
                                                    $firstName, 
                                                    $lastName, 
                                                    $nameJersay, 
                                                    $number, 
                                                    $position
                                                );

                    $newPlayer->setId($_POST["playerId"]);
                    $_SESSION["valide"] = "Joueur ajouté.";

                }

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
        }
    
        header('Location: index.php?route=adminClub');
        exit;
    }

    public function checkChangeNamePlayer(){
        if(isset($_POST["playerId"]) && 
        isset($_POST["changeFirst_name"]) &&
        isset($_POST["changeLast_name"]) &&
        isset($_POST["changeNameJersay"]) &&
        isset($_POST["changeNumberJersay"]) &&
        isset($_POST["changePoste"]))  {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $playerId = htmlspecialchars($_POST["playerId"]);
                $changeFirstName = htmlspecialchars($_POST["changeFirst_name"]);
                $changeLastName = htmlspecialchars($_POST["changeLast_name"]);
                $changeNameJersay = htmlspecialchars($_POST["changeNameJersay"]);
                $numberJersay = htmlspecialchars($_POST["changeNumberJersay"]);
                $changePoste = htmlspecialchars($_POST["changePoste"]);

                $playerManager = new NsMasiaManager();
                //$playerManager->changeNameJerseyPlayer();
            }
            
        }
    }

    public function checkChangeNameJerseyPlayer(){

    }

    public function checkChangeNumberPlayer(){

    }

    public function checkChangePositionPlayer(){

    }

    public function checkRemovePlayer(){
        if ( isset($_POST["playerId"])) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                $nsmasiaManager = new NsMasiaManager();
                $nsmasiaManager->removePlayer($_POST["playerId"]);

                $_SESSION["valide"] = "Joueur retirer.";

            }

        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
        }
    
        header('Location: index.php?route=adminClub');
        exit;
    }

    /***************************** Admin Boutique ***************************/
    public function checkAddproduct(){

    }

    public function checkChangeproduct(){
        
    }

    public function checkRemoveproduct(){
        
    }

}