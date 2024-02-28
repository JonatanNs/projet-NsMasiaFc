<?php

class ArticleController extends AbstractController
{
    public function news()
    {
        //appel Manager

        $this->render("news.html.twig", [
            
        ]);
    }

    public function club()
    {
        //appel Manager

        $this->render("club.html.twig", [
            
        ]);
    }
}