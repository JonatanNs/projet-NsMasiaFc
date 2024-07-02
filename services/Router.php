<?php

class Router
{
    public function handleRequest(array $get) : void
    {
        $authc = new AuthController();
        $homeC = new HomeController();
        $ac = new ArticleController();
        $merchController = new MerchController();
        $oc = new OrderController();
        $MatchController = new MatchController();
        $compteUserController = new CompteUserController();
        $pc = new PlayerNsMasiaController();

        $adm = new AdminController();
        $adminBoutiqueC = new AdminBoutiqueController();
        $adminBilletterieC = new AdminBilleterieController();
        $adminNewsC = new AdminNewsController();
        $adminManageC = new AdminManageController();
        $adminRivalTeamC = new AdminRivalTeamController();
        $adminNsMasiaC = new AdminNsMasiaController();

        $id = isset($get["id"]) ? (int)$get["id"] : 0;

        if(!isset($get["route"]))
        {
            $homeC->home();
        }
        /*********************************************************
                            * HOME PAGE *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "home")
        {
            $homeC->home();
        }
        else if(isset($get["route"]) && $get["route"] === "homeArticle" && isset($get["id"]))
        {
            $ac->homeArticle($id);
        }
        else if(isset($get["route"]) && $get["route"] === "allRanking")
        {
            $homeC->allRanking();
        }
        else if(isset($get["route"]) && $get["route"] === "legalNotice")
        {
            $homeC->legalNotice();
        }
        else if(isset($get["route"]) && $get["route"] === "generalConditionsSale")
        {
            $homeC->generalConditionsSale();
        }
        else if(isset($get["route"]) && $get["route"] === "cookies")
        {
            $homeC->cookies();
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
        else if(isset($get["route"]) && $get["route"] === "adminMatch" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adm->adminMatch();
        }
        else if(isset($get["route"]) && $get["route"] === "adminRivalTeam" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminRivalTeamC->adminRivalTeam();
        }
        else if(isset($get["route"]) && $get["route"] === "adminClub" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $pc->adminClub();
        }
        else if(isset($get["route"]) && $get["route"] === "adminActualite" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNewsC->adminActualite();
        }
        else if(isset($get["route"]) && $get["route"] === "adminBilletterie" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBilletterieC->adminBiletterie();
        }
        else if(isset($get["route"]) && $get["route"] === "adminBoutique" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBoutiqueC->adminBoutique();
        }
        else if(isset($get["route"]) && $get["route"] === "manageAdmin" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminManageC->adminManage();
        }
        else if(isset($get["route"]) && $get["route"] === "adminNsMasia" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminManageC->adminNsMasia();
        }
        /*****************************
            * Check Admin *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkChangeRole" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminManageC->checkChangeRole();
        }
        /*****************************
            * Check Admin NS MASIA *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkUpdateName" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNsMasiaC->checkUpdateName();
        }
        else if(isset($get["route"]) && $get["route"] === "checkUpdateLogo" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNsMasiaC->checkUpdateLogo();
        }
        else if(isset($get["route"]) && $get["route"] === "checkUpdateEmail" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNsMasiaC->checkUpdateEmail();
        }
        else if(isset($get["route"]) && $get["route"] === "checkUpdateBannerEmail" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNsMasiaC->checkUpdateBannerEmail();
        }
        /*****************************
            * Check Admin Club *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkaddPlayer" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $pc->checkaddPlayer();
        }
        else if(isset($get["route"]) && $get["route"] === "checkRemovePlayer" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $pc->checkRemovePlayer();
        }
        /*****************************
            * Check Admin Actuality *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkAddArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNewsC->checkAddArticle();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeTitleArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNewsC->checkChangeTitleArticle();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeContentArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNewsC->checkChangeContentArticle();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeImgArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNewsC->checkChangeImgArticle();
        }
        else if(isset($get["route"]) && $get["route"] === "checkRemoveArticle" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminNewsC->checkRemoveArticle();
        }
        /*****************************
            * Check Admin Biletterie *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkAddMatchs" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBilletterieC->checkAddMatchs();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeMatch" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBilletterieC->checkChangeMatch();
        }
        else if(isset($get["route"]) && $get["route"] === "checkRemoveMatch" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBilletterieC->checkRemoveMatch();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAddResult" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $MatchController->checkAddResult();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeResult" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $MatchController->checkChangeResult();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangePointRivalTeam" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminRivalTeamC->checkChangePointRivalTeam();
        }
        else if(isset($get["route"]) && $get["route"] === "checkAddRivalTeam" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminRivalTeamC->checkAddRivalTeam();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeLogoRivalTeam" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminRivalTeamC->checkChangeLogoRivalTeam();
        }
        else if(isset($get["route"]) && $get["route"] === "checkChangeNameRivalTeam" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminRivalTeamC->checkChangeNameRivalTeam();
        }
        else if(isset($get["route"]) && $get["route"] === "checkRemoveRivalTeam" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminRivalTeamC->checkRemoveRivalTeam();
        }
        /*****************************
            * Check Admin Boutique *
        *****************************/
        else if(isset($get["route"]) && $get["route"] === "checkAddproduct" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBoutiqueC->checkAddproduct();
        }
        else if(isset($get["route"]) && $get["route"] === "checkRemoveproduct" && isset($_GET['secret']) && $_GET['secret'] === $_ENV["SECRET"])
        {
            $adminBoutiqueC->checkRemoveproduct();
        }
        /*********************************************************
                            * LOGOUT *
        *********************************************************/
        else if(isset($get["route"]) && $get["route"] === "logout")
        {
            $authc->logout();
        } 
        else if(isset($get["route"]) && $get["route"] === "page404")
        {
            $authc->page404();

        } else{
            
            $authc->page404();
        }
    }
}

