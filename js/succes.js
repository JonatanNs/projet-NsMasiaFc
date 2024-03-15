function getBasket(){
    let basket = localStorage.getItem("basket");
    if(basket === null){
        return [];
    } else{
        return JSON.parse(basket);
    }
}

function getTotalPrice(){
    let basket = getBasket();
    let total = 0;
    for(let product of basket){
        total += product.quantity * product.prices;
    }
    return total;
}

const formPayementSucces = document.querySelector(".succesBtn");
    formPayementSucces.addEventListener("click", function(){
        localStorage.clear();
    })

//function InputHiddenFromPaySucces(){
        
const formSucces = document.querySelector(".formSucces");
const basketData = localStorage.getItem("basket");
const basket = JSON.parse(basketData);

if(basket){
    basket.forEach(product => {

        let productUser = {
            "name": product.name,
            "size" : product.size,
            "prices": parseInt(product.prices),
            "quantity": product.quantity,
            "total" : parseInt(product.prices)*product.quantity
        };
        
        let tableauJSON = JSON.stringify(productUser);
        if(formSucces){
            const inputQuantityHidden = document.createElement("input");
            inputQuantityHidden.type = "hidden";
            inputQuantityHidden.name = "products[]";
            inputQuantityHidden.value = tableauJSON ;
            formSucces.appendChild(inputQuantityHidden);
        }
    });
}

