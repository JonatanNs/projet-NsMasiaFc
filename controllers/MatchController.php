<?php

class MatchController extends AbstractController
{
    public function billeterie()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $matchManager = new MatchManager();
        $matchs = $matchManager->getAllMatchs();
        $this->render("biletterie/billeterie.html.twig", [
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

        $this->render("biletterie/reservation.html.twig", [
            'userIsConect' => $userIsConect,
            'matchs' => $matchs,
            'tickets' => $tickets
        ]);
    }

    public function payementTicket()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $matchManager = new MatchManager();
        $tickets = $matchManager->getAllTickets();

        $this->render("biletterie/payementTicket.html.twig", [
            'userIsConect' => $userIsConect,
            'tickets' => $tickets,
            'tokenCSRF' => $tokenCSRF
        ]);
    }

}