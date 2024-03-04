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

function changeQuantity(product, newQuantity) {
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
        total += product.quantity * product.price;
    }
    return total;
}


document.addEventListener('DOMContentLoaded', function(){
    const shirtOne = document.querySelector('.shirtOne'); //  le maillot
    const maillotId = shirtOne.getAttribute('id'); // ID du maillot
    const imgUrl = shirtOne.querySelector('img').getAttribute('src'); // URL de l'image 
    const imgAlt = shirtOne.querySelector('img').getAttribute('alt');
    const h2Element = document.querySelector('.shirtSale').querySelector('h2'); 
    const productName = h2Element.textContent; 
    const shirtSaleSection = document.querySelector('.shirtSale'); 
    const priceElement = shirtSaleSection.querySelector('.prices'); 
    const productPrice = priceElement.textContent; // prix
    const btnAjouterAuPannier = document.querySelector(".btn-ajouterAuPannier");
    const tailleSelect = shirtSaleSection.querySelector('#taille'); //ID taille

    const numberElement = shirtSaleSection.querySelector('.number'); 
    const numberValue = numberElement.textContent; // valeur de l'élément .number

    const plus = document.querySelector(".plus");
    const moins = document.querySelector(".moins");
    let num = 0;

    function basket(){
        tailleSelect.addEventListener('change', function() {
            const selectedSize = tailleSelect.value; // Obtenez la taille sélectionnée à partir de la valeur de l'option sélectionnée
            console.log(selectedSize); // Affichez la taille sélectionnée dans la console

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
                    //console.log(num);
                    
                });
            }

            if (moins) {
                moins.addEventListener("click", function() {
                    if (num > 0) {
                        num--;  
                        if(num < 10){
                            plus.style.display="block";
                        }
                    }
                    numberElement.textContent = num;
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

    const theBag = document.querySelector(".basket");

if (theBag) {
    theBag.addEventListener("click", function() {
        const textEmpty = document.querySelector(".textEmpty");
        const panier = document.querySelector(".pannier");
        const panierImg = document.querySelector(".panier-img");
        const panierName = document.querySelector(".panier-nameProduct");
        const panierPrice = document.querySelector(".panier-price");
        const panierQuantity = document.querySelector(".panier-quantity");
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
            panierName.innerHTML = "";
            panierPrice.innerHTML = "";
            panierQuantity.innerHTML = "";
            panierTotal.innerHTML = "";

            // Afficher chaque produit dans le panier
            basket.forEach(product => {
                // Créer un élément img pour l'image du produit
                let img = document.createElement("img");
                img.src = product.url;
                img.alt = product.alt;
                panierImg.appendChild(img);

                // Créer un élément span pour le nom du produit
                let name = document.createElement("p");
                name.textContent = product.name;
                panierName.appendChild(name);

                // Créer un élément span pour le prix du produit
                let price = document.createElement("p");
                price.textContent = product.prices;
                panierPrice.appendChild(price);

                // Créer un élément span pour la quantité du produit
                let quantity = document.createElement("p");
                quantity.textContent = product.quantity;
                panierQuantity.appendChild(quantity);

                // Créer un élément span pour le prix total du produit
                let total = document.createElement("p");
                total.textContent = product.quantity * product.prices;
                panierTotal.appendChild(total);
            });
        }
    });
}



    
    basket();

  
    
    
});







