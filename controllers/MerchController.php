<?php

class MerchController extends AbstractController
{
    public function billeterie()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;

        $this->render("billeterie.html.twig", [
            'userIsConect' => $userIsConect
        ]);
    }

    public function boutique()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;

        $this->render("boutique.html.twig", [
            'userIsConect' => $userIsConect
        ]);
    }
}