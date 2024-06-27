

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

function getProductLenght(){
    let basket = getBasket();
    let number = 0;
    for(let product of basket){
        number += product.length;
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
        const logoNs = document.querySelector(".logoNs");
        const theBag = document.querySelector(".bagProduct");
        if (theBag){
            theBag.addEventListener("click", function() {
                // Récupérer les données du panier depuis le LocalStorage
                const basketData = localStorage.getItem("basket");
                
                if(!basketData){
                    // Le panier est vide, afficher un message approprié
                    const p = document.createElement("p");
                    p.classList.add("basketEmpty");
                    p.textContent = "Votre panier est vide.";

                    logoNs.parentNode.insertBefore(p, logoNs); 
                    setTimeout(() => {
                        p.remove();
                    }, 1000);  
                }else {

                    const basket = JSON.parse(basketData);
                    const bagProductsUser = document.querySelector(".bagProductsUser");
                    const bagProducts = document.querySelector(".bagProducts");
                    const bagProductsTotal = document.querySelector(".bagProductsTotal");
                    // Le panier n'est pas vide, afficher son contenu

                    bagProducts.innerHTML = '';
                    bagProductsTotal.innerHTML = '';
                        
                    bagProductsUser.classList.toggle("invisible");
        
                    // Convertir les données récupérées en objet JavaScript
                    
            
                    // Afficher chaque produit dans le panier
                    basket.forEach(product => {
                        const firstLi = document.createElement("div");
                        firstLi.classList.add("firstLi");
                        bagProducts.appendChild(firstLi);
                        
                        const img = document.createElement("img")
                        img.src = product.url;
                        img.alt = product.alt;
                        firstLi.appendChild(img);

                        const nameProduct = document.createElement("h6");
                        nameProduct.classList.add("bagNameProduct");
                        nameProduct.textContent = product.name;
                        firstLi.appendChild(nameProduct);

                        const prixQuantite = document.createElement("p");
                        prixQuantite.classList.add("bagPriceQuantity");
                        prixQuantite.textContent = product.prices + " € x " + product.quantity;
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
                        bagProductsTotal.appendChild(totalp);
                    }
            });
        }  
    } 

    function switchShirts(){
        const backShirt = document.querySelectorAll(".backShirt");
        const frontShirt = document.querySelectorAll(".frontShirt");
        const allShirtTwo = document.querySelectorAll("div.shirts > section > div.frontShirt > figure.shirtTwo > img");
        const AllOne = document.querySelectorAll("div.shirts > section > div.backShirt > figure.shirtTwo img");

        allShirtTwo.forEach(shirtTwo => {
            shirtTwo.addEventListener("click", function() {
                backShirt.forEach(back => {
                    back.classList.remove("invisible");
                });
                frontShirt.forEach(front => {
                    front.classList.add("invisible");
                });
            });
        });

        AllOne.forEach(face => {
            face.addEventListener("click", function() {
                frontShirt.forEach(front => {
                    front.classList.remove("invisible");
                });
                backShirt.forEach(back => {
                    back.classList.add("invisible");
                });
            });
        });

        const productLength = document.querySelector(".productLength");
        if(productLength){
            const shirtsLength = parseInt(productLength.textContent); 
            for(let i = 1; i <= shirtsLength; i++){
                let otherShirts = document.querySelector(".other" + i);
                if(otherShirts){
                    otherShirts.addEventListener("click", function(){
                    
                        const shirts = document.querySelector(".shirts:not(.invisible)");
                        const shirts2 = document.querySelector(".shirts.invisible");
                        shirts.classList.add("invisible");
                        shirts2.classList.remove("invisible"); 
                    })
                } 
            }
        }
    }

    function updateProductCount() {
        const productCount = getNumberProduct();
        let number = document.querySelector(".numberLenghtProduct");
        const bagProduct = document.querySelector(".bagProduct");
    
        if (number) {
            number.textContent = productCount;
        } else {
            number = document.createElement("p");
            number.classList.add("numberLenghtProduct");
            number.textContent = productCount;
            if(bagProduct){
                bagProduct.appendChild(number);
            }

        }
    }
    
    function AddLocalStorageBoutique() {
        const productIdElements = document.querySelectorAll(".shirtOne");
        const shirtSale = document.querySelectorAll('.shirtSale');
        const size = document.getElementById("size");
        let sizeChoice = '';
    
        if (size) {
            size.addEventListener("change", function() {
                sizeChoice = size.value; 
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
                button.addEventListener("click", function() {
                    const shirtsId = "shirts" + (i + 1);
                    const productUrl = productImgUrl;
                    const productAlt = productImgAlt;
                    const productQuantity = "";  
    
                    const basketEmpty = document.querySelector(".basketEmpty");
                    if (basketEmpty) {
                        basketEmpty.classList.add("invisible");
                    }
    
                    if (sizeChoice) {
                        const logoNs = document.querySelector(".logoNs");
                        const p = document.createElement("p");
                        p.classList.add("productAddInBasket");
                        p.textContent = "Produit ajouté au panier.";
                        logoNs.parentNode.insertBefore(p, logoNs); 
                        setTimeout(() => {
                            p.remove();
                        }, 1000); 
    
                        addBasket({
                            id: productId,
                            name: productName.trim(),
                            url: productUrl,
                            alt: productAlt,
                            size: sizeChoice,
                            prices: parseFloat(productPrice),
                            quantity: productQuantity
                        });
    
                        // Mettre à jour le nombre de produits
                        updateProductCount();
                    }
                });
            });
        });
    }

    function lookProduct(){
        const lookProductButtons = document.querySelectorAll(".lookProduct");
        lookProductButtons.forEach(function(button) {
            document.querySelectorAll(".shirts").forEach(function(shirts) {
                shirts.classList.add("invisible");
            });
            button.addEventListener("click", function() {
                // Retrieve the product ID associated with this button
                const productId = button.closest(".shirtSale").querySelector(".shirtOne").getAttribute("id");
                // Masquer tous les shirtss
                document.querySelectorAll(".shirts").forEach(function(shirts) {
                    shirts.classList.add("invisible");
                });
                // Show shirts matching product ID
                document.querySelector(".shirts" + productId).classList.remove("invisible");
            });
        });
    }

    function cartUser(){
        const basketData = localStorage.getItem("basket");
        if(basketData){
            const basket = JSON.parse(basketData);
            if(basket){
                const bagProducts = document.querySelector(".cartsProducts");
                const cartQuantityValue = document.querySelector(".cartQuantityValue");
                if(cartQuantityValue){
                    cartQuantityValue.textContent = getTotalPrice() + "€";
                }
                    basket.forEach(product => {
                        const firstLi = document.createElement("li");
                        firstLi.classList.add("firstLi");
                        
                        const secondLi = document.createElement("li");
                        secondLi.classList.add("secondLi");
        
                        const ul = document.createElement("ul");

                        if(bagProducts) {
                            bagProducts.appendChild(firstLi);
                            bagProducts.appendChild(secondLi);
                            bagProducts.appendChild(ul);
                        }    
                        
                        const img = document.createElement("img")
                        img.src = product.url;
                        img.alt = product.alt;
                        firstLi.appendChild(img);

                        const nameProduct = document.createElement("h6");
                        nameProduct.classList.add("cartNameProduct");
                        nameProduct.textContent = product.name;
                        secondLi.appendChild(nameProduct);
                        
                        const prixQuantite = document.createElement("p");
                        prixQuantite.classList.add("cartPriceQuantity");
                        prixQuantite.textContent = product.prices + " €";
                        secondLi.appendChild(prixQuantite);
                        
                        const figure = document.createElement("figure");
                        figure.appendChild(img);
                        firstLi.appendChild(figure);
                        
                        const div = document.createElement("div"); 
                        div.classList.add("divCol");
                        secondLi.appendChild(div);
                        div.appendChild(prixQuantite);

                        const btnLess = document.createElement("button");
                        btnLess.classList.add("btnLess");
                        btnLess.classList.add("btn");
                        btnLess.dataset.id = product.id;
                        btnLess.textContent = "-";
                        div.appendChild(btnLess);

                        const p = document.createElement("p"); 
                        p.classList.add("quantityProduct");
                        p.textContent = product.quantity;
                        div.appendChild(p);

                        const btnAdd = document.createElement("button");
                        btnAdd.classList.add("btnAdd");
                        btnAdd.classList.add("btn");
                        btnAdd.dataset.id = product.id;
                        btnAdd.textContent = "+";
                        div.appendChild(btnAdd);
                        
                        ul.appendChild(firstLi);
                        ul.appendChild(secondLi);
                    });
                    const addQuantityButtons = document.querySelectorAll(".btnAdd");
                    const removeQuantityButtons = document.querySelectorAll(".btnLess");
                    const textQuantity = document.querySelectorAll(".quantityProduct");

                    addQuantityButtons.forEach((button, index) => {
                        button.addEventListener("click", function() {
                            let currentValue = parseInt(textQuantity[index].textContent); 
                            currentValue++; 
                            const productId = button.dataset.id;
                            textQuantity[index].textContent = currentValue; // Update text content
                            changeQuantity({id:"" + productId + ""}, +1);
                            cartQuantityValue.textContent = getTotalPrice() + " €";
                        });
                    });
                    removeQuantityButtons.forEach((button, index) => {
                        button.addEventListener("click", function() {
                            let currentValue = parseInt(textQuantity[index].textContent); 
                            currentValue--; 
                            const productId = button.dataset.id;
                            textQuantity[index].textContent = currentValue; // Update text content
                            changeQuantity({ id: productId }, -1);
                            cartQuantityValue.textContent = getTotalPrice() + " €";
                            if (currentValue === 0) {
                                let blockProduct = document.querySelector(".cartsProducts > ul:nth-child(" + (index + 1) + ")");
                                blockProduct.style.display = "none"; 
                                document.querySelector(".goPayement").style.display = "none";
                            }
                            if(getTotalPrice() === 0) {
                                localStorage.removeItem("basket");
                                document.querySelector(".goPayement").style.display = "none";
                            }
                        });
                    });     
            } 
        }
    }
    
    bagBoutique();
    AddLocalStorageBoutique();
    switchShirts();
    lookProduct();
    cartUser();
    AddLocalStorageBoutique();
    updateProductCount();

});