document.addEventListener('DOMContentLoaded', function(){
    // partie boutique
    const plus = document.querySelector(".plus");
    const number = document.querySelector(".number");
    const moins = document.querySelector(".moins");
    const btnAjouterAuPannier = document.querySelector(".btn-ajouterAuPannier");
    const prix = document.querySelector(".prix");
    const theBag = document.querySelector(".theBag");
    const numberBag = document.querySelector(".numberBag");
    const numberBagPanier = document.querySelector(".numberBagPanier");
    const search = document.querySelector(".search");
    const pannier = document.querySelector(".pannier");
    const boutiqueSearch = document.querySelector(".boutiqueSearch");
    const textEmpty = document.querySelector(".textEmpty");
    //form
    const goSignup = document.querySelector(".goSignup");
    const goLogin = document.querySelector(".goLogin");
    const formLogin = document.querySelector(".formLogin");
    const formSignup = document.querySelector(".formSignup");
    //menuMobile
    const menu = document.querySelector(".openMenu");
    const navMobile = document.querySelector(".navMobile");
    const closeMenu = document.querySelector(".closeMenu");
    const main = document.querySelector(".container");
    // recup product choisi
    const menu = document.querySelector(".openMenu");
    const navMobile = document.querySelector(".navMobile");
    const closeMenu = document.querySelector(".closeMenu");
    const main = document.querySelector(".container");

    function form() {
        // Vérifiez si les éléments existent
        if (goSignup && goLogin && formLogin && formSignup) {
            goSignup.addEventListener("click", function() {
                formSignup.classList.remove("invisible");
                formLogin.classList.add("invisible");
            });
    
            goLogin.addEventListener("click", function() {
                formLogin.classList.remove("invisible");
                formSignup.classList.add("invisible");
            });
        }
    }   

    function menuMobile() {
        // Vérifiez si les éléments existent
        if (menu && navMobile && closeMenu && main) {
            menu.addEventListener("click", function() {
                navMobile.classList.remove("invisible");
    
                closeMenu.addEventListener("click", function() {
                    navMobile.classList.add("invisible");
                });
            });
        }
    }

    function addCart(){
        let num = 0;

        plus.addEventListener("click", function() {
            if (num < 10) {
                num++;
            }
            if(num === 10){
            const p = document.createElement("p");
            const textp = document.createTextNode("Vous ne pouvez commander que jusqu'à 10 produits au maximum.");
            p.appendChild(textp);
            prix.append(p);
            plus.style.display="none";
            p.style.color="red";
            }
            number.textContent = num;
        });

        moins.addEventListener("click", function() {
            if (num > 0) {
                num--;
                if (num < 10) {
                    const p = prix.querySelector("p");
                    if (p) {
                        prix.removeChild(p);
                        plus.style.display = "block";
                    }
                }
            }
            number.textContent = num;
        });

        btnAjouterAuPannier.addEventListener("click", function() {
            numberBag.textContent = num;  
            numberBagPanier.textContent = num;
        });

        theBag.addEventListener("click", function() {
            if(num === 0){
                textEmpty.textContent = "Votre panier est vide";
                textEmpty.style.cssText = "white-space: nowrap; margin-left: -4em; margin-top: 1em;";
                setTimeout(function() {
                    textEmpty.textContent = "";
                }, 3000);
            } else{
                theBag.addEventListener("click", function() {
                    pannier.classList.toggle("invisible");
                    pannier.classList.add("pannier");
                    boutiqueSearch.classList.remove("boutiqueSearch");
                });
            }
        });

        search.addEventListener("click", function() {
            boutiqueSearch.classList.toggle("invisible");
            pannier.classList.remove("pannier");
            boutiqueSearch.classList.add("boutiqueSearch");
        });
        
    }
    

    form();
    menuMobile();
    addCart();
});