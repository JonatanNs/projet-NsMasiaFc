<?php

class MatchController extends AbstractController
{
    public function billeterie()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $matchManager = new MatchManager();
        $matchs = $matchManager->getAllMatchs();
        $this->render("billeterie.html.twig", [
            'userIsConect' => $userIsConect,
            'matchs' => $matchs
        ]);
    }

    public function reservation($id)
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $matchManager = new MatchManager();
        $matchs = $matchManager->getAllMatchsById($id);
        $tickets = $matchManager->getAllTickets();

        $this->render("reservation.html.twig", [
            'userIsConect' => $userIsConect,
            'matchs' => $matchs,
            'tickets' => $tickets
        ]);
    }

    public function payementTicket()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $matchManager = new MatchManager();
        $tickets = $matchManager->getAllTickets();

        $this->render("payementTicket.html.twig", [
            'userIsConect' => $userIsConect,
            'tickets' => $tickets
        ]);
    }

}