<?php

class PlayerNsMasiaController extends AbstractController{

    public function club(){
        $nsMasiaManager = new NsMasiaManager();
        $playersNs = $nsMasiaManager->getPlayerNsMasia();

        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;

        $this->render("club.html.twig", [
            'userIsConect' => $userIsConect,
            'playersNs' => $playersNs
        ]);
    }
}