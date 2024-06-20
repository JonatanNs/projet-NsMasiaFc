<?php 

class AdminRivalTeamController extends AbstractController{

    public function adminRivalTeam() : void {
        $nsMasiaManager = new NsMasiaManager();
        $matchManager = new MatchManager();
        $rivalTeamManager = new RivalTeamManager();

        $matchPlays = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();

        $rivalTeam = $rivalTeamManager->getAllRivalTeams();

        $matchs = $matchManager->getAllMatchs();

        $nsMasia = $nsMasiaManager->getNsMasia();

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];

        $this->render("Admin/adminRivalTeam.html.twig", [

            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'matchs' => $matchs,
            'rivalTeam' => $rivalTeam,
            'matchPlays' => $matchPlays,
            'resultMatchs' => $resultMatchs,
            'secret' => $secret
        ]);
    }

    public function checkAddRivalTeam() : void {
        $secret = $_ENV["SECRET"];
    
       // Verification of required fields
        if ( isset($_POST["nameTeam"]) && isset($_POST["imgAlt"]) ) {
            $tokenManager = new CSRFTokenManager(); 
    
            // CSRF token verification
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                
                $media = '';
    
                // Checking and processing the uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/img/uploadsRivalTeam/";
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
                        header("Location: index.php?route=adminRivalTeam&secret=$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location: index.php?route=adminRivalTeam&secret=$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location: index.php?route=adminRivalTeam&secret=$secret");
                            exit;
                        }
                    }
    
                    $media = 'assets/img/uploadsRivalTeam/' . htmlspecialchars($sanitizedFileName);
                    var_dump($media);
                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location: index.php?route=adminRivalTeam&secret=$secret");
                    exit;
                }
    
                // Article Data Processing
                $nameTeam = htmlspecialchars_decode($_POST["nameTeam"]);
                $imgAlt = htmlspecialchars_decode($_POST["imgAlt"]);
    
    
                // Creation of the article
                $rivalTeamManager = new RivalTeamManager();

                $newRivalTeam = new RivalTeam(
                                                $nameTeam,
                                                $media,
                                                $imgAlt
                                            );
                $rivalTeamManager->createRivalTeam($newRivalTeam);
    
                $_SESSION["valide"] = "Nouvel équipe ajouté.";
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
    
            } else {
                $_SESSION["error"] = "Une erreur de validation est survenue.";
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
            }
    
        } else {
            $_SESSION["error"] = "Une erreur est survenu.";
            header("Location: index.php?route=adminRivalTeam&secret=$secret");
            exit;
        }
    }
    public function checkChangePointRivalTeam() : void {
        
    }
    public function checkChangeLogoRivalTeam() : void {
        $secret = $_ENV["SECRET"];
    
       // Verification of required fields
        if ( isset($_POST["imgAlt"]) && isset($_POST["selectTeam"])) {
            $tokenManager = new CSRFTokenManager(); 
    
            // CSRF token verification
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                
                $media = '';
    
                // Checking and processing the uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/img/uploadsRivalTeam/";
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
                        header("Location: index.php?route=adminRivalTeam&secret=$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location: index.php?route=adminRivalTeam&secret=$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location: index.php?route=adminRivalTeam&secret=$secret");
                            exit;
                        }
                    }
    
                    $media = 'assets/img/uploadsRivalTeam/' . htmlspecialchars($sanitizedFileName);

                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location: index.php?route=adminRivalTeam&secret=$secret");
                    exit;
                }
    
                // Article Data Processing
                $imgAlt = htmlspecialchars_decode($_POST["imgAlt"]);
                $selectTeamId = htmlspecialchars_decode($_POST["selectTeam"]);
    
                // Creation of the article
                $rivalTeamManager = new RivalTeamManager();
                $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($selectTeamId);

                // Remove file from uploads directory
                $filePath = $rivalTeam->getLogoUrl();

                if(file_exists($filePath)) {
                    if(unlink($filePath)) {

                        $newRivalTeam = new RivalTeam(
                            $rivalTeam->getTeam(),
                            $media,
                            $imgAlt
                        );
                        $newRivalTeam->setId($rivalTeam->getId());
                        $rivalTeamManager->checkChangeLogoRivalTeam(
                                                                        $newRivalTeam->getId(), 
                                                                        $newRivalTeam->getLogoUrl(),
                                                                        $newRivalTeam->getLogoAlt()
                                                                    );
                        

                        $_SESSION["valide"] = "Logo de l'équipe : " . $newRivalTeam->getTeam() . "modifier.";
                    } else {
                        $_SESSION["error"] = "Une erreur est survenue lors de la suppression du fichier.";
                    }
                } else {
                    $_SESSION["error"] = "Fichier non trouvé.";
                }
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
    
            } else {
                $_SESSION["error"] = "Une erreur de validation est survenue.";
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
            }
    
        } else {
            $_SESSION["error"] = "Une erreur est survenu.";
            header("Location: index.php?route=adminRivalTeam&secret=$secret");
            exit;
        }
    }
    public function checkChangeNameRivalTeam() : void {
        $secret = $_ENV["SECRET"];
    
        // Verification of required fields
         if ( isset($_POST["nameTeam"]) && isset($_POST["selectTeam"])) {
             $tokenManager = new CSRFTokenManager(); 
     
             // CSRF token verification
             if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
     
                $nameTeam = htmlspecialchars($_POST["nameTeam"]);
                $selectTeamId = htmlspecialchars($_POST["selectTeam"]);
                
                 // Creation of the article
                $rivalTeamManager = new RivalTeamManager();
                $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($selectTeamId);

                $newRivalTeam = new RivalTeam(
                                                $nameTeam,
                                                $rivalTeam->getLogoUrl(),
                                                $rivalTeam->getLogoAlt()
                                            );
                $newRivalTeam->setId($rivalTeam->getId());

                $rivalTeamManager->checkChangeNameRivalTeam(
                                                                $newRivalTeam->getId(), 
                                                                $nameTeam
                                                            );
                
                $_SESSION["valide"] = "Nom de l'équipe modifier";
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
     
            } else {
                $_SESSION["error"] = "Une erreur de validation est survenue.";
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
            }
     
        } else {
            $_SESSION["error"] = "Une erreur est survenu.";
            header("Location: index.php?route=adminRivalTeam&secret=$secret");
            exit;
        }
    }

    public function checkRemoveRivalTeam() : void {
        $secret = $_ENV["SECRET"];
        
        // Check if the required field 'selectTeam' is set in the POST request
        if (isset($_POST["selectTeam"])) {
            $tokenManager = new CSRFTokenManager(); 
            
            // Check if the CSRF token is set and valid
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                // Sanitize the input from the POST request
                $selectTeamId = htmlspecialchars($_POST["selectTeam"]);
                
                // Create an instance of RivalTeamManager and get the team by ID
                $rivalTeamManager = new RivalTeamManager();
                $rivalTeam = $rivalTeamManager->getAllRivalTeamsById((int)$selectTeamId);
                
                // Attempt to remove the team
                $isRemoved = $rivalTeamManager->removeRivalTeam($rivalTeam->getId());
    
                if ($isRemoved) {
                    // Set a success message if the team was removed
                    $_SESSION["valide"] = "Équipe supprimée avec succès.";
                } else {
                    // If the team cannot be removed, change its status to "NO ACTIF"
                    $status = "NO ACTIF";
                    $rivalTeamManager->checkChangeStatusRivalTeam($rivalTeam->getId(), $status);
                    $_SESSION["valide"] = " L’équipe ne peut pas être retirée car elle est 
                                            référencée ailleurs, 
                                            mais il est maintenant marqué comme 'Non actif.";
                }
    
                // Redirect to the admin rival team page
                header("Location: index.php?route=adminRivalTeam&secret=$secret");
                exit;
            } else {
                // Set an error message if CSRF validation fails
                $_SESSION["error"] = "Une erreur est survenu.";
            }
        } else {
            // Set an error message if the required field is not set
            $_SESSION["error"] = "Une erreur est survenu.";
        }
        
        // Redirect to the admin rival team page if there was an error
        header("Location: index.php?route=adminRivalTeam&secret=$secret");
        exit;
    }
    
    

    public function checkPassivRivalTeam() : void {
        
    }
}