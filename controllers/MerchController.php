<?php

class MerchController extends AbstractController
{
    public function billeterie()
    {
        //appel Manager

        $this->render("billeterie.html.twig", [
            
        ]);
    }

    public function boutique()
    {
        //appel Manager

        $this->render("boutique.html.twig", [
            
        ]);
    }
}