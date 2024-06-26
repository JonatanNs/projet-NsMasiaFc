document.addEventListener('DOMContentLoaded', function(){

    window.addEventListener('scroll', handleScroll);

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
        const menu = document.querySelector('.openMenu');
        const closeMenu = document.querySelector('.closeMenu');
        const navMobile = document.querySelector('.navMobile');
        const headerUserConnect = document.querySelector(".header-userConnect a"); 
        // Vérifiez si les éléments existent
        if (menu) {
            menu.addEventListener("click", function() {
                navMobile.style.display = "block";
                document.body.classList.add('no-scroll'); // Ajoute la classe pour désactiver le scroll
    
                const links = document.querySelectorAll("header > div > nav > div > ul > li > a");
                // document.querySelector("body > header > figure img").style.display = "block";
                links.forEach(link => {
                    link.style.color = "black";
                });
                headerUserConnect.style.color="white";
            });
    
            closeMenu.addEventListener("click", function() {
                navMobile.style.display = "none";
                document.body.classList.remove('no-scroll'); // Supprime la classe pour réactiver le scroll
            });
        }
    }

    function toggleInvisible(item){
        item.classList.toggle("invisible");
    }

    function pageClub() {
        const historyClubNav = document.querySelector('.historyClubNav');
        const playersClubNav = document.querySelector('.playersClubNav');
        const historyClub = document.querySelector('.historyClub');
        const playersClub = document.querySelector('.playersClub');
    
        if (playersClubNav && historyClubNav) {
            playersClubNav.addEventListener("click", function() {
                playersClub.classList.remove("invisible");
                historyClub.classList.add("invisible");
            });
    
            historyClubNav.addEventListener("click", function() {
                historyClub.classList.remove("invisible");
                playersClub.classList.add("invisible");
            });
        }
    }
    
    function handleScroll() {
        const scrollPosition = window.scrollY; // Position actuelle de défilement
        const header = document.querySelector('.header');
        
        const links = document.querySelectorAll("header > div > nav > div > ul > li > a")
        const headerUserConnect = document.querySelector(".header-userConnect a"); 

        if (scrollPosition > 90) { // 90px du haut
            
            header.style.backgroundColor="rgb(255, 249, 237)";
            header.style.transition="all 0.5s ease";
            links.forEach(link =>{
                link.style.color="black";
            });
            if(headerUserConnect){
                headerUserConnect.style.color="white";
            }
           
            
        } else if(scrollPosition > 10) {
            
            header.style.backgroundColor="transparent";
            links.forEach(link =>{
                link.style.color="white";
            });
            if(headerUserConnect){
                headerUserConnect.style.color="white";
            }
        } 
    }

    function handleNextMatch() {
        const nextMatchButton = document.querySelector('.btnProchainMatch button');
        const targetElement = document.querySelector('.buttonNextPrevious');
    
        if(nextMatchButton && targetElement) {
            nextMatchButton.addEventListener("click", function() {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            });
        }
    }

    function sliderSlick(){
        $('.multiple-items').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        infinite: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

    }

    handleNextMatch()
    pageClub();
    form();
    menuMobile();
    sliderSlick();
    
});