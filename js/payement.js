document.addEventListener('DOMContentLoaded', function(){

    function cartPayement(){
        const lookCart = document.querySelector("#lookCart");
        const recapCartUser = document.querySelector(".recapCartUser");

        const basketData = localStorage.getItem("basket");
            const basket = JSON.parse(basketData);
        if(lookCart){
            lookCart.addEventListener("click", function(){
                recapCartUser.classList.toggle("invisible");
    
                recapCartUser.innerHTML = "";
    
                basket.forEach(product => {

                    const article = document.createElement("article");
                    article.classList.add("recapCartUserCol");
                    recapCartUser.appendChild(article);

                    const figure = document.createElement("figure");
                    figure.classList.add("recapCartUserImg");
                    article.appendChild(figure);

                    const img = document.createElement("img");
                    img.src = product.url;
                    img.alt = product.alt;
                    figure.appendChild(img);
                    
                    // Créer un élément h6 pour le nom du produit
                    const nameProduct = document.createElement("h5");
                    nameProduct.classList.add("cartNameProduct");
                    nameProduct.textContent = product.name;
                    article.appendChild(nameProduct);
                
                    // Créer un élément p pour le prix du produit
                    const prixQuantite = document.createElement("p");
                    prixQuantite.classList.add("cartPriceQuantity");
                    prixQuantite.textContent = product.prices + " € x " + product.quantity;
                    article.appendChild(prixQuantite);
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
    
    cartPayement();
    InputHidden();
});


