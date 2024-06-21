<?php

class PlayerNsMasiaController extends AbstractController{

    public function club() : void{
        $secret = $_ENV["SECRET"];
        $nsMasiaManager = new NsMasiaManager();
        $playersNs = $nsMasiaManager->getPlayerNsMasia();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;

        $this->render("club.html.twig", [
            'userIsConect' => $userIsConect,
            'playersNs' => $playersNs,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function adminClub() : void {
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

        $nsMasiaManager = new NsMasiaManager();
        $players = $nsMasiaManager->getPlayerNsMasia();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("Admin/adminClub.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'players' => $players,
            'secret' => $secret,
            'nsMasia' => $nsMasia 
        ]);
    }

    public function checkAddPlayer() {
        $secret = $_ENV["SECRET"];
        if (
            isset($_POST["addFirst_name"]) && 
            isset($_POST["addLast_name"]) &&
            isset($_POST["addNameJersay"]) && 
            isset($_POST["addNumberJersay"]) &&
            isset($_POST["addPoste"])
        ) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $media = '';
    
                // Checking and processing the uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/img/uploadsPlayer/";
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
                        header("Location:  index.php?route=adminClub&secret=$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location:  index.php?route=adminClub&secret=$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location:  index.php?route=adminClub&secret=$secret");
                            exit;
                        }
                    }
    
                    $media = 'assets/img/uploadsPlayer/' . htmlspecialchars($sanitizedFileName);
                    
                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location:  index.php?route=adminClub&secret=$secret");
                    exit;
                }

                $firstName = htmlspecialchars($_POST["addFirst_name"]);
                $lastName = htmlspecialchars($_POST["addLast_name"]);
                $nameJersay = htmlspecialchars($_POST["addNameJersay"]);
                $number = htmlspecialchars($_POST["addNumberJersay"]);
                $position = htmlspecialchars($_POST["addPoste"]);
    
                $nsmasiaManager = new NsMasiaManager();
    
                if ($nsmasiaManager->isJerseyNumberExists($number)) {
                    $_SESSION["error"] = "Un joueur a déjà ce numéro de maillot.";
                } else {
                    $newPlayer = new PlayerNsMasia( 
                                                    $firstName, 
                                                    $lastName, 
                                                    $nameJersay, 
                                                    $number, 
                                                    $position, 
                                                    $media
                                                );

                    $nsmasiaManager->createPlayer($newPlayer);

                    $_SESSION["valide"] = "Joueur ajouté.";
                }
            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
        }
    
        header("Location: index.php?route=adminClub&secret=$secret");
        exit;
    }

    public function checkRemovePlayer() : void {
        $secret = $_ENV["SECRET"];
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
    
        header("Location: index.php?route=adminClub&secret=$secret");
        exit;
    }
}