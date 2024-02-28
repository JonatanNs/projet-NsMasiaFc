document.addEventListener('DOMContentLoaded', function(){
    let formLogin = document.querySelector(".formLogin");
    let closeForm = document.querySelector("#closeForm");
    let formSignup = document.querySelector(".formSignup");
    let goSignup = document.querySelector(".goSignup");

    closeForm.addEventListener("click", function(event){
        formLogin.style.display="none";
        closeForm.style.display="none";
        formSignup.style.display="none";
    });
    goSignup.addEventListener("click", function(event){
        formSignup.style.display="block";
        formLogin.style.display="none";
        
    });

});
