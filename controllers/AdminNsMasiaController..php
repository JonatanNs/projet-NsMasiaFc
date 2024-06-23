<?php 

class AdminNsMasiaController extends AbstractController{
    public function checkUpdateName() : void{
        $secret = $_ENV["SECRET"];
        if(
            isset($_POST["updateName"]) && isset($_POST["updateConfirmName"]) 
        ){
           
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 
                
                if($_POST["updateName"] === $_POST["updateConfirmName"]){

                    $updateConfirmName = htmlspecialchars_decode($_POST["updateConfirmName"]);

                    $nsMasiaManager = new NsMasiaManager();
                    $nsMasia = $nsMasiaManager->getNsMasia();

                    $newNsMasia = new NsMasia(
                                                $updateConfirmName, 
                                                $nsMasia->getLogoUrl(), 
                                                $nsMasia->getLogoAlt(), 
                                                $nsMasia->getEmail(), 
                                                $nsMasia->getPasswordEmail(),
                                                $nsMasia->getStadium()
                                            );
                    $newNsMasia->setId($nsMasia->getId());
                    $newNsMasia->setBannerEmail($nsMasia->getBannerEmail());
                    
                    $nsMasiaManager->updateNsMasiaName($newNsMasia->getId(), $newNsMasia->getName());

                    $_SESSION["valide"] = "Succès de la Mise à Jour.";
                    header("Location: Admin-site-$secret");
                    exit;

                } else{
                    $_SESSION["error"] = "Le nom de confirmation et le nom ne corresponde pas.";
                    header("Location: Admin-site-$secret");
                    exit;
                }  
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-site-$secret");
                exit;
            } 
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: 404");
            exit;
        } 
    }

    public function checkUpdateEmail() : void{
        $secret = $_ENV["SECRET"];
        if(
            isset($_POST["updateNewEmail"]) && 
            isset($_POST["updateConfirmNewEmail"]) && 
            isset($_POST["actualEmail"]) && 
            isset($_POST["updateNewPasswordEmail"])
        ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 
                
                if($_POST["updateNewEmail"] === $_POST["updateConfirmNewEmail"]){

                    $actualEmail = htmlspecialchars($_POST["actualEmail"]);
                    $newPasswordEmail = htmlspecialchars($_POST["updateNewPasswordEmail"]);
                    $newConfirmNewEmail = htmlspecialchars($_POST["updateConfirmNewEmail"]);

                    $nsMasiaManager = new NsMasiaManager();
                    $nsMasia = $nsMasiaManager->getNsMasia();

                    if($nsMasia->getEmail() === $actualEmail){
                    
                        $newNsMasia = new NsMasia(
                                                    $nsMasia->getName(), 
                                                    $nsMasia->getLogoUrl(), 
                                                    $nsMasia->getLogoAlt(), 
                                                    $newConfirmNewEmail, 
                                                    $newPasswordEmail,
                                                    $nsMasia->getStadium()
                                                );
                                    $newNsMasia->setId($nsMasia->getId());
                                    $newNsMasia->setBannerEmail($nsMasia->getBannerEmail());

                        $nsMasiaManager->updateNsMasiaEmail(
                                                                $newNsMasia->getId(), 
                                                                $newNsMasia->getEmail(),
                                                                $newNsMasia->getPasswordEmail()
                                                            );

                        $_SESSION["valide"] = "Succès de la Mise à Jour.";
                        header("Location: Admin-site-$secret");
                        exit;

                    } else{
                        $_SESSION["error"] = "L'email ne correspond pas à l'actuel.";
                        header("Location: Admin-site-$secret");
                        exit;
                    } 
                } else{
                    $_SESSION["error"] = "L’email de confirmation et l’email ne correspondent pas.";
                    header("Location: Admin-site-$secret");
                    exit;
                }  
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-site-$secret");
                exit;
            } 
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: 404");
            exit;
        } 
    }

    public function checkUpdateLogo() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["logoAlt"]) ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 

                $media = '';
    
                // Checking and processing the uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/img/uploads/";
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
                        header("Location:  Admin-site-$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location:  Admin-site-$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location:  Admin-site-$secret");
                            exit;
                        }
                    }
    
                    $media = 'assets/img/uploads/' . htmlspecialchars($sanitizedFileName);
                    
                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location:  Admin-site-$secret");
                    exit;
                }

        
                $logoAlt = htmlspecialchars_decode($_POST["logoAlt"]);

                $nsMasiaManager = new NsMasiaManager();
                $nsMasia = $nsMasiaManager->getNsMasia();

                // Remove file from uploads directory
                $filePath = $nsMasia->getLogoUrl();

                if(file_exists($filePath)) {
                    if(unlink($filePath)) {
                        $newNsMasia = new NsMasia(
                                                    $nsMasia->getName(), 
                                                    $media, 
                                                    $logoAlt, 
                                                    $nsMasia->getEmail(), 
                                                    $nsMasia->getPasswordEmail(),
                                                    $nsMasia->getStadium()
                                                );
                        $newNsMasia->setId($nsMasia->getId());
                        $newNsMasia->setBannerEmail($nsMasia->getBannerEmail());

                        $nsMasiaManager->updateNsMasiaLogo(
                                                            $newNsMasia->getId(), 
                                                            $newNsMasia->getLogoUrl(), 
                                                            $newNsMasia->getLogoAlt() 
                                                        );

                        $_SESSION["valide"] = "Succès de la Mise à Jour.";

                    } else {
                        $_SESSION["error"] = "Une erreur est survenue lors de la suppression du fichier.";
                    }
                } else {
                    $_SESSION["error"] = "Fichier non trouvé.";
                }
                header("Location: Admin-site-$secret");
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-site-$secret");
                exit;
            } 
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: 404");
            exit;
        } 
    }

    public function checkUpdateBannerEmail() : void {
        $secret = $_ENV["SECRET"];
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 

                $media = '';
    
                // Checking and processing the uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/img/uploads/";
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
                        header("Location:  Admin-site-$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location:  Admin-site-$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location:  Admin-site-$secret");
                            exit;
                        }
                    }
    
                    $media = 'assets/img/uploads/' . htmlspecialchars($sanitizedFileName);
                    
                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location:  Admin-site-$secret");
                    exit;
                }

                $nsMasiaManager = new NsMasiaManager();
                $nsMasia = $nsMasiaManager->getNsMasia();

                // Remove file from uploads directory
                $filePath = $nsMasia->getBannerEmail();

                if(file_exists($filePath)) {
                    if(unlink($filePath)) {
                        $newNsMasia = new NsMasia(
                                                    $nsMasia->getName(), 
                                                    $nsMasia->getLogoUrl(), 
                                                    $nsMasia->getLogoAlt(), 
                                                    $nsMasia->getEmail(), 
                                                    $nsMasia->getPasswordEmail(),
                                                    $nsMasia->getStadium()
                                                );
                        $newNsMasia->setId($nsMasia->getId());
                        $newNsMasia->setBannerEmail($media);

                        $nsMasiaManager->updatebannerEmail(
                                                            $newNsMasia->getId(), 
                                                            $newNsMasia->getBannerEmail()
                                                        );

                        $_SESSION["valide"] = "Succès de la Mise à Jour.";

                    } else {
                        $_SESSION["error"] = "Une erreur est survenue lors de la suppression du fichier.";
                    }
                } else {
                    $_SESSION["error"] = "Fichier non trouvé.";
                }
                header("Location: Admin-site-$secret");
                exit;

            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-site-$secret");
                exit;
            }  
    }
}