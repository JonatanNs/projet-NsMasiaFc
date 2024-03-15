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
        else if(isset($get["route"]) && $get["route"] === "home")
        {
            $authc->home();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAdmin")
        {
            $authc->checkAdmin();
        }
        else if(isset($get["route"]) && $get["route"] === "succes")
        {
            $oc->succesPay();
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
        else if(isset($get["route"]) && $get["route"] === "lookProduct" && isset($get["id"]))
        {
            $productId = isset($get["id"]) ? (int)$get["id"] : 0;
            $mc->boutiqueProduct($productId);
        }
        else if(isset($get["route"]) && $get["route"] === "billeterie")
        {
            $mc->billeterie();
        }
        else if(isset($get["route"]) && $get["route"] === "reservation")
        {
            $mc->reservation();
        }
        else if(isset($get["route"]) && $get["route"] === "panier")
        {
            $oc->panier();
        }
        else if(isset($get["route"]) && $get["route"] === "payement")
        {
            $oc->payement();
        }
        else if(isset($get["route"]) && $get["route"] === "stripePay")
        {
            $oc->stripePayement();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAddress")
        {
            $oc->checkAddress();
        }
        else if(isset($get["route"]) && $get["route"] === "checkSucces")
        {
            $oc->checkSucces();
        }
        else if(isset($get["route"]) && $get["route"] === "form")
        {
            $authc->form();
        }
        else if(isset($get["route"]) && $get["route"] === "check-login")
        {
            $authc->checkLogin();
        }
        else if(isset($get["route"]) && $get["route"] === "check-signup")
        {
            $authc->checkSignup();
        }
        else if(isset($get["route"]) && $get["route"] === "logout")
        {
            $authc->logout();
        }
    }
}