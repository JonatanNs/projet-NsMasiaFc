document.addEventListener('DOMContentLoaded', function(){

    function form(){
        const goSignup = document.querySelector(".goSignup");
        const goLogin = document.querySelector(".goLogin");
        const formLogin = document.querySelector(".formLogin");
        const formSignup = document.querySelector(".formSignup");

        goSignup.addEventListener("click", function(){
            formSignup.classList.remove("invisible");
            formLogin.classList.add("invisible");
        });

            goLogin.addEventListener("click", function(){
                formLogin.classList.remove("invisible");
                formSignup.classList.add("invisible");
        });  
    }
    


    const menu = document.querySelector(".openMenu");
    const navMobile  = document.querySelector(".navMobile");
    const closeMenu  = document.querySelector(".closeMenu");
    

    menu.addEventListener("click", function(event){
        navMobile.classList.remove("invisible");
        
        closeMenu.addEventListener("click", function(event){
            navMobile.classList.add("invisible"); 
        });
        
    });

    form();

});



