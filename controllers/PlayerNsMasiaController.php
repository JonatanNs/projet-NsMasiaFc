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
}