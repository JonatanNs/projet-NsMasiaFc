document.addEventListener('DOMContentLoaded', function(){

    //form
    const goSignup = document.querySelector(".goSignup");
    const goLogin = document.querySelector(".goLogin");
    //menuMobile
    const menu = document.querySelector(".openMenu");
    const closeMenu = document.querySelector(".closeMenu");
    
    function form() {
        // Vérifiez si les éléments existent
        if(goSignup){
            goSignup.addEventListener("click", function() {
                document.querySelector(".formSignup").classList.remove("invisible");
                document.querySelector(".formLogin").classList.add("invisible");
            });
        }
        if(goLogin){
            goLogin.addEventListener("click", function() {
                document.querySelector(".formLogin").classList.remove("invisible");
                document.querySelector(".formSignup").classList.add("invisible");
            });
        }
    }   

    function menuMobile() {
        // Vérifiez si les éléments existent
        if(menu){
            menu.addEventListener("click", function() {
                document.querySelector(".navMobile").classList.remove("invisible");
    
                closeMenu.addEventListener("click", function() {
                    document.querySelector(".navMobile").classList.add("invisible");
                });
            });
        }
    }

    
    form();
    menuMobile();
    
});