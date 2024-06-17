<?php

class MerchController extends AbstractController
{

    public function boutique() : void
    {
        $secret = $_ENV["SECRET"];
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("boutique/boutique.html.twig", [
            'userIsConect' => $userIsConect,
            'products' => $products,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function boutiqueProduct($id) : void
    { 
        $secret = $_ENV["SECRET"];
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $merchManager = new MerchManager();
        
        $products = $merchManager->getProductsById($id);

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        
        // Rendre la vue en passant les détails du produit à afficher
        $this->render("boutique/boutiqueProduct.html.twig", [
            'userIsConect' => $userIsConect,
            'products' => $products,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }
}