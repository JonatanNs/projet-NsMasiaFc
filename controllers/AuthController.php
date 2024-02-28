<?php

class AuthController extends AbstractController
{
    public function home()
    {
        //appel Manager

        $this->render("home.html.twig", [
            
        ]);
    }

    public function formLogin()
    {
        //appel Manager

        $this->render("form.html.twig", [
            
        ]);
    }

    public function formSignup()
    {
        //appel Manager

        $this->render("form.html.twig", [
            
        ]);
    }

    public function checkLogin() {

        
    }

    public function checkSignup() {

        
    }
}