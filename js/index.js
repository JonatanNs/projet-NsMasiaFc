document.addEventListener('DOMContentLoaded', function(){

    //form
    const goSignup = document.querySelector(".goSignup");
    const goLogin = document.querySelector(".goLogin");
    //menuMobile
    const menu = document.querySelector(".openMenu");
    const closeMenu = document.querySelector(".closeMenu");

    const allArticle = document.querySelector('.allArticle');
    const btnPreviousSlide = document.querySelector('.btnPreviousSlide');
    const btnNextSlide = document.querySelector('.btnNextSlide');
    const diapo = document.querySelector('.diapo');

    let articles = document.querySelectorAll(".article");
    let slideIndex = 0;
    let timer; // Déclarer la variable timer à l'extérieur des fonctions pour la rendre accessible

    function translate() {
        const slideWidth = allArticle.getBoundingClientRect().width;
        if (slideWidth) {
            const translateValue = -slideIndex * slideWidth;
            let transitionSlide = diapo.dataset.transition;
            articles.forEach((article, index) => {
                article.style.transform = `translateX(${translateValue}px)`;
                article.style.transition = "all " + transitionSlide + "s";
            });
        }
    }

    function previousSlide() {
        if (slideIndex === 0) {
            return; // Ne rien faire si nous sommes déjà sur la première diapositive
        }
        slideIndex--;
        translate(); 
    }

    function nextSlide() {
        slideIndex++;
        translate();
        
        // Réinitialiser la position du slider une fois arrivé au clone
        if (slideIndex >= articles.length - 1) {
            setTimeout(() => {
                slideIndex = 0;
                articles.forEach((article, index) => {
                    article.style.transform = 'translateX(0)';
                    article.style.transition = "unset";
                });
            }, 2000); // Ajoutez un délai pour que la transition soit terminée avant la réinitialisation
        }
    }

    function stopTimer() {
        clearInterval(timer);
    }

    if (diapo) {
        // Cloner le premier article et l'ajouter à la fin
        let firstImg = allArticle.firstElementChild.cloneNode(true);
        allArticle.appendChild(firstImg);

        articles = allArticle.querySelectorAll(".article");
        let speed = diapo.dataset.speed;
        timer = setInterval(nextSlide, speed);

        btnPreviousSlide.addEventListener('click', () => {
            previousSlide();
            stopTimer();
        });

        btnNextSlide.addEventListener('click', () => {
            nextSlide();
            stopTimer();
        });
    }
    
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