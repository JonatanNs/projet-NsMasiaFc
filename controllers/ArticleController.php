<?php

class ArticleController extends AbstractController
{
    public function news()
    {
        

        $this->render("news.html.twig", [
            
        ]);
    }

    public function club()
    {
        

        $this->render("club.html.twig", [
            
        ]);
    }
}