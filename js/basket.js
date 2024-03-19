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

function changeQuantity(product, quantity) {
    let basket = getBasket();
    let foundProduct = basket.find(p => p.id === product.id);
    if (foundProduct != undefined) {
        foundProduct.quantity += quantity;
        if(foundProduct.quantity <=0){
            removeFromBasket(foundProduct);
        } else{
            saveBasket(basket);
        }   
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
 
    function bagBoutique(){
        const theBag = document.querySelector(".bagProduct");
        if (theBag){
            theBag.addEventListener("click", function() {
                // Récupérer les données du panier depuis le LocalStorage
                const basketData = localStorage.getItem("basket");
                if(!basketData){
                    // Le panier est vide, afficher un message approprié
                    const p = document.createElement("p");
                    p.textContent = "Votre panier est vide";
                    p.style.cssText = "white-space: nowrap; color:red; margin: 1em 0 0 -4em ";
                    theBag.appendChild(p);   
                }else{
                    const panier = document.querySelector(".pannier");
                    const panierUser = document.querySelector(".panierUser");
                    const panierTotal = document.querySelector(".panier-total");
                    // Le panier n'est pas vide, afficher son contenu

                    panierUser.innerHTML = '';
                    panierTotal.innerHTML = '';
                    
                    panier.classList.toggle("invisible");
        
                    // Convertir les données récupérées en objet JavaScript
                    const basket = JSON.parse(basketData);
            
                    // Afficher chaque produit dans le panier
                    basket.forEach(product => {
                        const firstLi = document.createElement("div");
                        firstLi.classList.add("firstLi");
                        panierUser.appendChild(firstLi);
                        
                        const img = document.createElement("img")
                        img.src = product.url;
                        img.alt = product.alt;
                        firstLi.appendChild(img);
                        //img.appendChild(panierImg);

                        const nameProduct = document.createElement("h6");
                        nameProduct.classList.add("panier-nameProduct");
                        nameProduct.textContent = product.name;
                        //pannierDivCol.appendChild(nameProduct);
                        firstLi.appendChild(nameProduct);

                        const prixQuantite = document.createElement("p");
                        prixQuantite.classList.add("panier-price-quantity");
                        prixQuantite.textContent = product.prices + " € x " + product.quantity;
                        //pannierDivCol.appendChild(prixQuantite);
                        firstLi.appendChild(prixQuantite);

                        const figure = document.createElement("figure");
                        firstLi.appendChild(figure);
                        figure.appendChild(img);

                        const div = document.createElement("div"); 
                        div.classList.add("divCol");
                        firstLi.appendChild(div);
                        div.appendChild(nameProduct);
                        div.appendChild(prixQuantite);
                        
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
        const maillotDos = document.querySelectorAll(".maillotDos");
        const maillotFace = document.querySelectorAll(".maillotFace");
        const allShirtTwo = document.querySelectorAll("div.maillot > section > div.maillotFace > figure.shirtTwo > img");
        const AllOne = document.querySelectorAll("div.maillot > section > div.maillotDos > figure.shirtTwo img");

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
        if(productLength){
            const maillotLength = parseInt(productLength.textContent); 
            for(let i = 1; i <= maillotLength; i++){
                let otherMaillot = document.querySelector(".other" + i);
                if(otherMaillot){
                    otherMaillot.addEventListener("click", function(){
                    
                        let maillot = document.querySelector(".maillot:not(.invisible)");
                        let maillot2 = document.querySelector(".maillot.invisible");
                        maillot.classList.add("invisible");
                        maillot2.classList.remove("invisible"); 
                    })
                } 
            }
        }
    }

    function AddLocalStorageBoutique() {
        const productIdElements = document.querySelectorAll(".shirtOne");
        const shirtSale = document.querySelectorAll('.shirtSale');
        const taille = document.getElementById("taille");
    
        if (taille) {
            taille.addEventListener("change", function() {
                size = taille.value; 
            });
        }
    
        shirtSale.forEach((element, index) => {
            const productName = element.querySelector('h2').textContent;
            const productId = productIdElements[index].getAttribute("id");
            const productImgUrl = element.querySelector('img').getAttribute('src');
            const productImgAlt = element.querySelector('img').getAttribute('alt');
            const productPrice = element.querySelector('.prices').textContent;
    
            const addCartButtons = element.querySelectorAll(".addCart");
    
            addCartButtons.forEach((button, i) => {
                const productLength = document.querySelector('.productLength');
                button.addEventListener("click", function() {
                    const maillotId = "maillot" + (i + 1);
                    const productUrl = productImgUrl;
                    const productAlt = productImgAlt;
                    const productQuantity = ""; 
    
                    for (let j = 0; j < parseInt(productLength.textContent); j++) {
                        if (maillotId === "maillot" + (j + 1)) {
                            addBasket({
                                id: productId,
                                name: productName.trim(),
                                url: productUrl,
                                alt: productAlt,
                                size: size, 
                                prices: productPrice,
                                quantity: productQuantity
                            });
                            break; 
                        }
                    }
                });
            });
        });
    }
    
    function lookProduct(){
        const lookProductButtons = document.querySelectorAll(".lookProduct");
        lookProductButtons.forEach(function(button) {
            document.querySelectorAll(".maillot").forEach(function(maillot) {
                maillot.classList.add("invisible");
            });
            button.addEventListener("click", function() {
                // Récupérer l'ID du produit associé à ce bouton
                const productId = button.closest(".shirtSale").querySelector(".shirtOne").getAttribute("id");
                // Masquer tous les maillots
                document.querySelectorAll(".maillot").forEach(function(maillot) {
                    maillot.classList.add("invisible");
                });
                // Afficher le maillot correspondant à l'ID du produit
                document.querySelector(".maillot" + productId).classList.remove("invisible");
            });
        });
    }

    function panierUser(){
        const basketData = localStorage.getItem("basket");
        if(basketData){
            const basket = JSON.parse(basketData);
            if(basket){
                const panierUser = document.querySelector(".panierUser");
                const panierValue = document.querySelector(".panierValue");
                if(panierValue){
                    panierValue.textContent = getTotalPrice() + "€";
                }
                    basket.forEach(product => {
                        const firstLi = document.createElement("li");
                        firstLi.classList.add("firstLi");
                        panierUser.appendChild(firstLi);

                        const secondLi = document.createElement("li");
                        secondLi.classList.add("secondLi");
                        panierUser.appendChild(secondLi);

                        const ul = document.createElement("ul");
                        panierUser.appendChild(ul);
                        
                        const img = document.createElement("img")
                        img.src = product.url;
                        img.alt = product.alt;
                        firstLi.appendChild(img);

                        const nameProduct = document.createElement("h6");
                        nameProduct.classList.add("panier-nameProduct");
                        nameProduct.textContent = product.name;
                        secondLi.appendChild(nameProduct);
                        
                        const prixQuantite = document.createElement("p");
                        prixQuantite.classList.add("panier-price-quantity");
                        prixQuantite.textContent = product.prices + " €";
                        secondLi.appendChild(prixQuantite);
                        
                        const figure = document.createElement("figure");
                        figure.appendChild(img);
                        firstLi.appendChild(figure);
                        
                        const div = document.createElement("div"); 
                        div.classList.add("divCol");
                        secondLi.appendChild(div);
                        div.appendChild(prixQuantite);

                        const btnMoins = document.createElement("button");
                        btnMoins.classList.add("btnMoins");
                        btnMoins.classList.add("btn");
                        btnMoins.dataset.id = product.id;
                        btnMoins.textContent = "-";
                        div.appendChild(btnMoins);

                        const p = document.createElement("p"); 
                        p.classList.add("quantityProduct");
                        p.textContent = product.quantity;
                        div.appendChild(p);

                        const btnPlus = document.createElement("button");
                        btnPlus.classList.add("btnPlus");
                        btnPlus.classList.add("btn");
                        btnPlus.dataset.id = product.id;
                        btnPlus.textContent = "+";
                        div.appendChild(btnPlus);
                        
                        ul.appendChild(firstLi);
                        ul.appendChild(secondLi);
                    });
                    const addQuantityButtons = document.querySelectorAll(".btnPlus");
                    const removeQuantityButtons = document.querySelectorAll(".btnMoins");
                    const textQuantity = document.querySelectorAll(".quantityProduct");

                    addQuantityButtons.forEach((button, index) => {
                        button.addEventListener("click", function() {
                            let currentValue = parseInt(textQuantity[index].textContent); 
                            currentValue++; 
                            const productId = button.dataset.id;
                            textQuantity[index].textContent = currentValue; // Mettre à jour le contenu textuel
                            changeQuantity({id:"" + productId + ""}, +1);
                            panierValue.textContent = getTotalPrice() + " €";
                        });
                    });
                    removeQuantityButtons.forEach((button, index) => {
                        button.addEventListener("click", function() {
                            let currentValue = parseInt(textQuantity[index].textContent); 
                            currentValue--; 
                            const productId = button.dataset.id;
                            textQuantity[index].textContent = currentValue; // Mettre à jour le contenu textuel
                            changeQuantity({ id: productId }, -1);
                            panierValue.textContent = getTotalPrice() + " €";
                            if (currentValue === 0) {
                                let blockProduct = document.querySelector(".panierUser ul:nth-child(" + (index + 1) + ")");
                                blockProduct.style.display = "none";  
                            }
                            if(getTotalPrice() === 0) {
                                localStorage.removeItem("basket");
                            }
                        });
                    });     
            } 
        }
    }
    

    bagBoutique();
    AddLocalStorageBoutique();
    switchMaillot();
    lookProduct();
    panierUser();

});


