<?php

class OrderController extends AbstractController
{
    public function payement()
    {
        //appel Manager

        $this->render("payement.html.twig", [
            
        ]);
    }
}