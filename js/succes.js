document.addEventListener('DOMContentLoaded', function(){

const formSucces = document.querySelector(".formSucces");
const basketData = localStorage.getItem("basket");
const basket = JSON.parse(basketData);
const ticketData = localStorage.getItem("ticket");
const tickets = JSON.parse(ticketData);
const formPayementSucces = document.querySelector(".succesBtn");
        
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
                inputQuantityHidden.id = "productsBoutique";
                inputQuantityHidden.value = tableauJSON ;
                formSucces.appendChild(inputQuantityHidden);
            }
        });
    }else if(tickets){
    
        tickets.forEach(ticket =>{
            let ticketUser = {
                "ticketId": ticket.id,
                "matchId":ticket.matchId,
                "match":ticket.match,
                "tribune":ticket.tribune,
                "quantity":parseInt(ticket.quantity),
                "prices":parseInt(ticket.prices),
                "totalPrices": parseInt(ticket.prices)*ticket.quantity
            };
                    
            let tableauJSON = JSON.stringify(ticketUser);
            const arrayTicket = document.createElement("input");
            arrayTicket.type = "hidden";
            arrayTicket.name= "arrayTickets[]";
            arrayTicket.id= "ticketsBoutique";
            arrayTicket.value= tableauJSON;
            formSucces.appendChild(arrayTicket);
        });
    }

    const ticketsBoutique = document.getElementById("ticketsBoutique");
    const productsBoutique = document.getElementById("productsBoutique");

    formPayementSucces.addEventListener("click", function(){
        if(ticketsBoutique){
            localStorage.removeItem("ticket");
        } else if(productsBoutique){
            localStorage.removeItem("basket");
        }
    });
});
        
    

