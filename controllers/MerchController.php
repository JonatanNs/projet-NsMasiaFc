<?php

class MerchController extends AbstractController
{

    public function boutique()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();
        $this->render("boutique.html.twig", [
            'userIsConect' => $userIsConect,
            'products' => $products
        ]);
    }

    public function boutiqueProduct($id)
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $merchManager = new MerchManager();
        
        // Utiliser l'ID du produit pour récupérer les détails du produit spécifique
        $products = $merchManager->getAllProductsById($id);
        
        // Rendre la vue en passant les détails du produit à afficher
        $this->render("boutiqueProduct.html.twig", [
            'userIsConect' => $userIsConect,
            'products' => $products
        ]);
    }
}