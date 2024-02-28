<?php

class Router
{
    public function handleRequest(array $get) : void
    {

        $authc = new AuthController();
        $ac = new ArticleController();
        $mc = new MerchController();
        $oc = new OrderController();

        if(!isset($get["route"]))
        {
            $authc->home();
        }
        else if(isset($get["route"]) && $get["route"] === "news")
        {
            $ac->news();
        }
        else if(isset($get["route"]) && $get["route"] === "club")
        {
            $ac->club();
        }
        else if(isset($get["route"]) && $get["route"] === "boutique")
        {
            $mc->boutique();
        }
        else if(isset($get["route"]) && $get["route"] === "billeterie")
        {
            $mc->billeterie();
        }
        else if(isset($get["route"]) && $get["route"] === "payement")
        {
            $oc->payement();
        }
        else if(isset($get["route"]) && $get["route"] === "formLogin")
        {
            $authc->formLogin();
        }

        else if(isset($get["route"]) && $get["route"] === "formSignup")
        {
            $authc->formSignup();
        }

        else if(isset($get["route"]) && $get["route"] === "check-login")
        {
            $authc->checkLogin();
        }
        else if(isset($get["route"]) && $get["route"] === "check-signup")
        {
            $authc->checkSignup();
        }
    }
}