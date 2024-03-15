document.addEventListener('DOMContentLoaded', function(){

    function panierPayement(){
        const vertical = document.querySelector("#lookPanier");
        const recapPanierUser = document.querySelector(".recapPanierUser");

        const recapPanierUserImg = document.querySelector(".recapPanierUserImg");
        const recapPanierUserDivCol = document.querySelector(".recapPanierUserDivCol");
        const recapPanierUserTotal = document.querySelector(".recapPanierUser-total");
        const basketData = localStorage.getItem("basket");
            const basket = JSON.parse(basketData);
        if(vertical){
            vertical.addEventListener("click", function(){
                recapPanierUser.classList.toggle("invisible");
    
                recapPanierUserTotal.innerHTML ="";
                recapPanierUserImg.innerHTML = "";
                recapPanierUserDivCol.innerHTML = "";
    
                basket.forEach(product => {
                    const img = document.createElement("img");
                    img.src = product.url;
                    img.alt = product.alt;
                    recapPanierUserImg.appendChild(img);
                    // Créer un élément h6 pour le nom du produit
                    
                    const nameProduct = document.createElement("h4");
                    nameProduct.classList.add("panier-nameProduct");
                    nameProduct.textContent = product.name;
                    recapPanierUserDivCol.appendChild(nameProduct);
                
                    // Créer un élément p pour le prix du produit
                    const prixQuantite = document.createElement("p");
                    prixQuantite.classList.add("panier-price-quantity");
                    prixQuantite.textContent = product.prices + " € x " + product.quantity;
                    recapPanierUserDivCol.appendChild(prixQuantite);
                });
                
           });
        }
        
        
    }

    function InputHidden(){
        const totalPrice = document.getElementById("totalPrice");
        if(totalPrice){
            totalPrice.textContent = "Total " +  getTotalPrice() + "€";
        }
        
        const formStripe = document.querySelector(".formStripe");
        const basketData = localStorage.getItem("basket");
        const basket = JSON.parse(basketData);

        if(basket){
            basket.forEach(product => {

                let productUser = {
                    "name": product.name,
                    "size" : 'Taille ' + product.size,
                    "prices": parseInt(product.prices)*100,
                    "quantity": product.quantity
                };
                
                let tableauJSON = JSON.stringify(productUser);
                if(formStripe){
                    const inputQuantityHidden = document.createElement("input");
                    inputQuantityHidden.type = "hidden";
                    inputQuantityHidden.name = "products[]";
                    inputQuantityHidden.value = tableauJSON ;
                    formStripe.appendChild(inputQuantityHidden);
                }
                
            });
        }
        
    }
    

    
    panierPayement();
    InputHidden();
});


