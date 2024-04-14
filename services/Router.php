<?php

class Router
{
    public function handleRequest(array $get) : void
    {
        $authc = new AuthController();
        $ac = new ArticleController();
        $merchController = new MerchController();
        $oc = new OrderController();
        $adm = new AdminController();
        $MatchController = new MatchController();
        $compteUserController = new CompteUserController();
        $pc = new PlayerNsMasiaController();
        $id = isset($get["id"]) ? (int)$get["id"] : 0;

        if(!isset($get["route"]))
        {
            
            $authc->home();
        }
        /*********************************************************
                            * HOME PAGE *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "home")
        {
            $authc->home();
        }
        else if(isset($get["route"]) && $get["route"] === "homeArticle" && isset($get["id"]))
        {
            $ac->homeArticle($id);
        }
        else if(isset($get["route"]) && $get["route"] === "allRanking")
        {
            $authc->allRanking();
        }
        /*********************************************************
                            * FORM *
        *********************************************************/
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
        /*********************************************************
                            * used account *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "compteUser")
        {
            $compteUserController->compteUser();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeName")
        {
            $compteUserController->checkChangeName();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangerEmail")
        {
            $compteUserController->checkChangerEmail();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangerPassword")
        {
            $compteUserController->checkChangerPassword();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeAddress")
        {
            $compteUserController->checkChangeAddress();
        }
        /*********************************************************
                            * ARTICLE NEWS  *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "news")
        {
            $ac->news();
        }
        /*********************************************************
                            * CLUB *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "club")
        {
            $pc->club();
        }
        /*********************************************************
                            * SHOP *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "boutique")
        {
            $merchController->boutique();
        }
        else if(isset($get["route"]) && $get["route"] === "lookProduct" && isset($get["id"]))
        {
            $merchController->boutiqueProduct($id);
        }
        else if(isset($get["route"]) && $get["route"] === "payement")
        {
            $oc->payement();
        }
        else if(isset($get["route"]) && $get["route"] === "stripePay")
        {
            $oc->stripePayement();
        }
        
        /*********************************************************
                            * Bileterrie *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "billeterie")
        {
            $MatchController->billeterie();
        }
        else if(isset($get["route"]) && $get["route"] === "reservation" && isset($get["id"]))
        {
            $MatchController->reservation($id);
        }
        else if(isset($get["route"]) && $get["route"] === "payementTicket")
        {
            $MatchController->payementTicket();
        }
        
        else if(isset($get["route"]) && $get["route"] === "stripePayTicket")
        {
            $oc->stripePayTicket();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAddress")
        {
            $oc->checkAddress();
        }

        /******** CART ********* */
        else if(isset($get["route"]) && $get["route"] === "panier")
        {
            $oc->panier();
        }
        /***************** */
        /*********************************************************
                            * PEYEMENT SUCCES *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "checkSucces")
        {
            $oc->checkSucces();
        }
        else if(isset($get["route"]) && $get["route"] === "succes")
        {
            $oc->succesPay();
        }
        /*********************************************************
                            * ESPACE ADMIN * Page
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "checkAdmin")
        {
            $adm->checkAdmin();
        }
        else if(isset($get["route"]) && $get["route"] === "adminHome")
        {
            $adm->adminHome();
        }
        else if(isset($get["route"]) && $get["route"] === "adminClub")
        {
            $adm->adminClub();
        }
        else if(isset($get["route"]) && $get["route"] === "adminActualite")
        {
            $adm->adminActualite();
        }
        else if(isset($get["route"]) && $get["route"] === "adminBiletterie")
        {
            $adm->adminBiletterie();
        }
        else if(isset($get["route"]) && $get["route"] === "adminBoutique")
        {
            $adm->adminBoutique();
        }
        /*****************************
            * Check Home *
        *****************************/

        /*****************************
            * Check Club *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkaddPlayer")
        {
            $adm->checkaddPlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangePlayer")
        {
            $adm->checkChangePlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeNamePlayer")
        {
            $adm->checkChangeNamePlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeNameJerseyPlayer")
        {
            $adm->checkChangeNameJerseyPlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeNumberPlayer")
        {
            $adm->checkChangeNumberPlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangePositionPlayer")
        {
            $adm->checkChangePositionPlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkRemovePlayer")
        {
            $adm->checkRemovePlayer();
        }

        /*****************************
            * Check Actuality *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkAddArticle")
        {
            $adm->checkAddArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeArticle")
        {
            $adm->checkChangeAllArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeTitleArticle")
        {
            $adm->checkChangeTitleArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeContentArticle")
        {
            $adm->checkChangeContentArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeImgArticle")
        {
            $adm->checkChangeImgArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkRemoveArticle")
        {
            $adm->checkRemoveArticle();
        }
        /*****************************
            * Check Biletterie *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkAddResult")
        {
            $adm->checkAddResult();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAddMatchs")
        {
            $adm->checkAddMatchs();
        }
        /*****************************
            * Check Boutique *
        *****************************/

        

        /*********************************************************
                            * LOGOUT *
        *********************************************************/

        else if(isset($get["route"]) && $get["route"] === "logout")
        {
            $authc->logout();
        }
    }
}

