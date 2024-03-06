//sauvegarder le pannier dans le Stockage local pour y accéder console->appli->stockage local
function saveBasket(basket){
    localStorage.setItem("basket", JSON.stringify(basket));
}

//recuperer le Stockage local du pannier
function getBasket(){
    let basket = localStorage.getItem("basket");
    if(basket === null){
        return [];
    } else{
        return JSON.parse(basket);
    }
}

//ajouter un produit au panier
function addBasket(product){
    let basket = getBasket();
    let foundProduct = basket.find(p => p.id == product.id);
    if(foundProduct != undefined){
        foundProduct.quantity++;
    } else{
        product.quantity = 1;
        basket.push(product);
    } 
    saveBasket(basket);
}

function removeFromBasket(product){
    let basket = getBasket();
    basket = basket.filter(p => p.id != product.id);
    saveBasket(basket);
}

function changeQuantity(product, newQuantity){
    let basket = getBasket();
    let foundProductIndex = basket.findIndex(p => p.id === product.id);

    if (foundProductIndex !== -1) {
        if (newQuantity <= 0) {
            basket.splice(foundProductIndex, 1); 
        } else {
            basket[foundProductIndex].quantity = newQuantity; 
        }
        saveBasket(basket); 
    }
}

function getNumberProduct(){
    let basket = getBasket();
    let number = 0;
    for(let product of basket){
        number += product.quantity;
    }
    return number;
}


function getTotalPrice(){
    let basket = getBasket();
    let total = 0;
    for(let product of basket){
        total += product.quantity * product.prices;
    }
    return total;
}


document.addEventListener('DOMContentLoaded', function(){  
    const shirtOne = document.querySelector('.shirtOne'); //  le maillot
    const shirtSaleSection = document.querySelector('.shirtSale'); 
    const maillotId = shirtOne.getAttribute('id'); // ID du maillot
    const imgUrl = shirtOne.querySelector('img').getAttribute('src'); // URL de l'image 
    const imgAlt = shirtOne.querySelector('img').getAttribute('alt');
    const h2Element = document.querySelector('.shirtSale').querySelector('h2'); 
    const productName = h2Element.textContent; 
    const theBag = document.querySelector(".basket");
    const priceElement = shirtSaleSection.querySelector('.prices'); 
    const productPrice = priceElement.textContent; // prix

    const btnAjouterAuPannier = document.querySelector(".btn-ajouterAuPannier");
    const tailleSelect = shirtSaleSection.querySelector('#taille'); //ID taille
    const numberElement = shirtSaleSection.querySelector('.number'); 
    const plus = document.querySelector(".plus");
    const moins = document.querySelector(".moins");
    //switchMaillot
    const maillotDos = document.querySelectorAll(".maillotDos");
    const maillotFace = document.querySelectorAll(".maillotFace");
    const allShirtTwo = document.querySelectorAll("div.maillot > section > div.maillotFace > figure.shirtTwo > img");
    const AllOne = document.querySelectorAll("div.maillot > section > div.maillotDos > figure.shirtTwo img");
    

    let num = 0;

    function basketContent(){
        tailleSelect.addEventListener('change', function() {
        const selectedSize = tailleSelect.value; // Obtenez la taille sélectionnée à partir de la valeur de l'option sélectionnée
                
            if(plus){
                plus.addEventListener("click", function(){
                    num++;  
                    if(num === 10){
                        const p = document.createElement("p");
                        p.classList.add("maxProducts");
                        const textp = document.createTextNode("Vous ne pouvez commander que jusqu'à 10 produits au maximum.");
                        setTimeout(function() {
                            p.remove();
                        }, 3000);
                        p.appendChild(textp);
                        document.querySelector("section.shirtSale > ul > li:nth-child(5)").append(p);
                        plus.style.display="none";
                        p.style.color="red";
                    }       
                    numberElement.textContent = num;           
                });
            };

                if (moins) {
                        moins.addEventListener("click", function() {
                            if (num > 0) {
                                num--;  
                                if(num < 10){
                                    plus.style.display="block";
                                }
                            }
                            numberElement.forEach(number =>{
                                numberElement.textContent = num;
                            });
                        });
                    }
                   
                    btnAjouterAuPannier.addEventListener("click", function() {
                        if (num > 0) {
                            addBasket({
                                id: maillotId,
                                "name": productName,
                                "url": imgUrl,
                                "alt": imgAlt,
                                "size": selectedSize,
                                "prices": productPrice,
                                "quantity": num // Utiliser la quantité actuelle spécifiée par l'utilisateur
                            });
                            changeQuantity({ id: maillotId }, num); // Mettre à jour la quantité du produit dans le panier
                        }
                    });                  
            });
        }       
    
    

    
    function panier(){
        if (theBag) {
            theBag.addEventListener("click", function() {
                const textEmpty = document.querySelector(".textEmpty");
                const panier = document.querySelector(".pannier");
                const panierImg = document.querySelector(".panier-img");
                const pannierDivCol = document.querySelector(".pannier-divCol");
                const panierTotal = document.querySelector(".panier-total");
        
                // Récupérer les données du panier depuis le LocalStorage
                const basketData = localStorage.getItem("basket");
        
                if (!basketData) {
                    // Le panier est vide, afficher un message approprié
                    textEmpty.textContent = "Votre panier est vide";
                    textEmpty.style.cssText = "white-space: nowrap; position: absolute; top: 1em; right: -4.5em;";
                    setTimeout(function() { textEmpty.textContent = ""; }, 3000);
                } else {
                    // Le panier n'est pas vide, afficher son contenu
                    panier.classList.toggle("invisible");
        
                    // Convertir les données récupérées en objet JavaScript
                    const basket = JSON.parse(basketData);
        
                    // Vider le contenu actuel du panier avant d'ajouter de nouveaux éléments
                    panierImg.innerHTML = "";
                    panierTotal.innerHTML = "";
                    pannierDivCol.innerHTML ="";
                    
                    
                    // Afficher chaque produit dans le panier
                    basket.forEach(product => {
                        const img = document.createElement("img");
                        img.src = product.url;
                        img.alt = product.alt;
                        panierImg.appendChild(img);
                        // Créer un élément h6 pour le nom du produit
                        
                        const nameProduct = document.createElement("h6");
                        nameProduct.classList.add("panier-nameProduct");
                        nameProduct.textContent = product.name;
                        pannierDivCol.appendChild(nameProduct);
        
                        // Créer un élément p pour le prix du produit
                        const prixQuantite = document.createElement("p");
                        prixQuantite.classList.add("panier-price-quantity");
                        prixQuantite.textContent = product.prices + " € x " + product.quantity;
                        pannierDivCol.appendChild(prixQuantite);
                        
                    });
                    const totalp = document.createElement("p");
                        totalp.classList.add("totalp");
                        totalp.textContent = "Total = " +  getTotalPrice() + "€";
                        panierTotal.appendChild(totalp);
                }
            });
        }  
    } 
    
    

    function switchMaillot(){

        allShirtTwo.forEach(shirtTwo => {
            shirtTwo.addEventListener("click", function() {
                maillotDos.forEach(dos => {
                    dos.classList.remove("invisible");
                });
                maillotFace.forEach(face => {
                    face.classList.add("invisible");
                });
            });
        });

        AllOne.forEach(face => {
            face.addEventListener("click", function() {
                maillotFace.forEach(face => {
                    face.classList.remove("invisible");
                });
                maillotDos.forEach(dos => {
                    dos.classList.add("invisible");
                });
            });
        });

        const productLength = document.querySelector(".productLength");
        const maillotLength = parseInt(productLength.textContent); 

        for(let i = 1; i <= maillotLength; i++){
            if (i !== 1) { 
            let maillot = document.querySelector(".maillot" + i).classList.add("invisible");
            }
            let otherMaillot = document.querySelector(".other" + i);
            otherMaillot.addEventListener("click", function(){
                let maillot = document.querySelector(".maillot:not(.invisible)");
                let maillot2 = document.querySelector(".maillot.invisible");
                maillot.classList.add("invisible");
                maillot2.classList.remove("invisible"); 
            })
        }
    }

    function recherche(){
        const recherche = document.querySelector(".boutiqueSearch");
        const search = document.querySelector(".search");

        search.addEventListener("click", function() {
            recherche.classList.toggle("invisible");
        });
    };


    basketContent();
    panier();
    switchMaillot();
    recherche();
    


});







