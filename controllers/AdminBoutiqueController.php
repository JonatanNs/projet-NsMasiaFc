<?php 

class AdminBoutiqueController extends AbstractController{

    public function adminBoutique() : void {
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
        $nsMasia = $nsMasiaManager->getNsMasia();

        $productManager = new MerchManager();
        $products = $productManager->getAllProducts();

        $this->render("Admin/adminBoutique.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'secret' => $secret,
            'nsMasia' => $nsMasia,
            'products' => $products
        ]);
    }

    public function checkAddproduct() : void {
        $secret = $_ENV["SECRET"];
        if(
            isset($_POST["nameProduct"]) && 
            isset($_POST["imgAlt"]) && 
            isset($_POST["ortherImgAlt"]) && 
            isset($_POST["description"]) &&
            isset($_POST["prices"])
            ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
    
                $media1 = '';
                $media2 = '';
    
                // Checking and processing the first uploaded file
                if (isset($_FILES["addMediaFile"]["error"]) && $_FILES["addMediaFile"]["error"] === UPLOAD_ERR_OK ) {
                    $targetDir = "assets/img/uploadsProduct/";
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
    
                    $originalFileName1 = basename($_FILES["addMediaFile"]["name"]);
                    $sanitizedFileName1 = str_replace(' ', '_', $originalFileName1);
                    $targetFile1 = $targetDir . $sanitizedFileName1;
                    $imageFileType1 = strtolower(pathinfo($targetFile1, PATHINFO_EXTENSION));
    
                    // Image file validation
                    $check1 = getimagesize($_FILES["addMediaFile"]["tmp_name"]);
                    if ($check1 === false) {
                        $_SESSION["error"] = "Le fichier n’est pas une image.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType1, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile1)) {
                        if (!move_uploaded_file($_FILES["addMediaFile"]["tmp_name"], $targetFile1)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre fichier.";
                            header("Location: index.php?route=adminActualite&secret=$secret");
                            exit;
                        }
                    }
    
                    $media1 = 'assets/img/uploadsProduct/' . htmlspecialchars($sanitizedFileName1);
                } elseif ($_FILES["addMediaFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du fichier.";
                    header("Location: index.php?route=adminActualite&secret=$secret");
                    exit;
                }
    
                // Checking and processing the second uploaded file
                if (isset($_FILES["addMediaFile2"]["error"]) && $_FILES["addMediaFile2"]["error"] === UPLOAD_ERR_OK ) {
                    $targetDir = "assets/img/uploadsProduct/";
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
    
                    $originalFileName2 = basename($_FILES["addMediaFile2"]["name"]);
                    $sanitizedFileName2 = str_replace(' ', '_', $originalFileName2);
                    $targetFile2 = $targetDir . $sanitizedFileName2;
                    $imageFileType2 = strtolower(pathinfo($targetFile2, PATHINFO_EXTENSION));
    
                    // Image file validation
                    $check2 = getimagesize($_FILES["addMediaFile2"]["tmp_name"]);
                    if ($check2 === false) {
                        $_SESSION["error"] = "Le deuxième fichier n’est pas une image.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Validation of the file format
                    if (!in_array($imageFileType2, ["jpg", "png", "jpeg", "gif"])) {
                        $_SESSION["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés pour le deuxième fichier.";
                        header("Location: index.php?route=adminActualite&secret=$secret");
                        exit;
                    }
    
                    // Move the uploaded file if it does not exist
                    if (!file_exists($targetFile2)) {
                        if (!move_uploaded_file($_FILES["addMediaFile2"]["tmp_name"], $targetFile2)) {
                            $_SESSION["error"] = "Une erreur est survenue lors du téléchargement de votre deuxième fichier.";
                            header("Location: index.php?route=adminActualite&secret=$secret");
                            exit;
                        }
                    }
    
                    $media2 = 'assets/img/uploadsProduct/' . htmlspecialchars($sanitizedFileName2);
                } elseif ($_FILES["addMediaFile2"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    // Handling other download errors
                    $_SESSION["error"] = "Une erreur est survenue lors du téléchargement du deuxième fichier.";
                    header("Location: index.php?route=adminActualite&secret=$secret");
                    exit;
                }
    
                // You can now use $media1 and $media2 variables to save the media paths in the database or process them further.

                $nameProduct = htmlspecialchars($_POST["nameProduct"]);
                $imgAlt = htmlspecialchars_decode($_POST["imgAlt"]);
                $ortherImgAlt = htmlspecialchars_decode($_POST["ortherImgAlt"]);
                $description = nl2br(htmlspecialchars_decode($_POST["description"]));
                $prices = htmlspecialchars($_POST["prices"]);

                $newProduct = new Product(
                                            $nameProduct,
                                            $media1,
                                            $imgAlt,
                                            $description,
                                            $prices
                                        );

                $newProduct->setOtherImgUrl($media2);
                $newProduct->setOtherImgAlt($ortherImgAlt);

                $productManager = new MerchManager();
                $productManager->createProduct($newProduct);

    
                $_SESSION["valide"] = "Nouveau produit ajouté.";
                header("Location: index.php?route=adminBoutique&secret=$secret");
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminBoutique&secret=$secret");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: index.php?route=adminBoutique&secret=$secret");
            exit;
        } 
    }
    

    public function checkRemoveproduct() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["selectProduct"]) ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $selectProduct = htmlspecialchars($_POST["selectProduct"]);

                $productManager = new MerchManager();
                $product = $productManager->getProductsById($selectProduct);
                // Remove file from uploads directory
                $filePath = $product->getImgUrl();

                if(file_exists($filePath)) {
                    if(unlink($filePath)) {

                        $productManager->removeProduct($product->getId());

                        $_SESSION["valide"] = "Suppression réussie.";
                    } else {
                        $_SESSION["error"] = "Une erreur est survenue lors de la suppression du fichier.";
                    }
                } else {
                    $_SESSION["error"] = "Fichier non trouvé.";
                }
                header("Location: index.php?route=adminBoutique&secret=$secret");
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminBoutique&secret=$secret");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: index.php?route=adminBoutique&secret=$secret");
            exit;
        }
    }
}