<?php

class Router
{
    public function handleRequest(array $get) : void
    {
        $authc = new AuthController();
        $ac = new ArticleController();
        $merchController = new MerchController();
        $oc = new OrderController();
        $MatchController = new MatchController();
        $compteUserController = new CompteUserController();
        $pc = new PlayerNsMasiaController();

        $adm = new AdminController();
        $adminClub = new AdminClubController();
        $adminBoutique = new AdminBoutiqueController();
        $adminBilletterie = new AdminBilleterieController();
        $adminNews = new AdminNewsController();

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
        else if(isset($get["route"]) && $get["route"] === "checkAdmin" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adm->checkAdmin();
        }
        
        else if(isset($get["route"]) && $get["route"] === "adminHome" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adm->adminHome();
        }
        else if(isset($get["route"]) && $get["route"] === "adminClub" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminClub->adminClub();
        }
        else if(isset($get["route"]) && $get["route"] === "adminActualite" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNews->adminActualite();
        }
        else if(isset($get["route"]) && $get["route"] === "adminBiletterie" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBilletterie->adminBiletterie();
        }
        else if(isset($get["route"]) && $get["route"] === "adminBoutique" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBoutique->adminBoutique();
        }
        /*****************************
            * Check Home *
        *****************************/

        /*****************************
            * Check Club *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkaddPlayer" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminClub->checkaddPlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangePlayer" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminClub->checkChangePlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeNamePlayer" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminClub->checkChangeNamePlayer();
        }

        else if(isset($get["route"]) && $get["route"] === "checkRemovePlayer" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminClub->checkRemovePlayer();
        }

        /*****************************
            * Check Actuality *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkAddArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNews->checkAddArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeTitleArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNews->checkChangeTitleArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeContentArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNews->checkChangeContentArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkChangeImgArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNews->checkChangeImgArticle();
        }

        else if(isset($get["route"]) && $get["route"] === "checkRemoveArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNews->checkRemoveArticle();
        }
        /*****************************
            * Check Biletterie *
        *****************************/
        
        else if(isset($get["route"]) && $get["route"] === "checkAddMatchs" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBilletterie->checkAddMatchs();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAddResult" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $MatchController->checkAddResult();
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

