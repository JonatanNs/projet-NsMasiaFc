document.addEventListener('DOMContentLoaded', function(){
    const lookMyOrder = document.querySelector(".myOrder > h2 > span");
    const yourTickets = document.querySelector(".yourTickets");
    const yourProducts = document.querySelector(".yourProducts");
    const yourTicketsOrder = document.querySelectorAll(".yourTickets-order");
    const yourTicketsInfo = document.querySelectorAll(".yourTickets-info");
    const yourProductsOrder = document.querySelectorAll(".yourProducts-order");
    const yourProductsInfo = document.querySelectorAll(".yourProducts-info");

    if(lookMyOrder){
        lookMyOrder.addEventListener("click", function(){
            yourProducts.classList.toggle("invisible");
            yourTickets.classList.toggle("invisible");
            
            yourTickets.addEventListener("click", function(){
                yourTicketsOrder.forEach(orderTickets =>{
                    orderTickets.classList.toggle("invisible");
                });
                yourTicketsInfo.forEach(ticket =>{
                    ticket.classList.toggle("invisible");
                });
            });
            yourProducts.addEventListener("click", function(){
                yourProductsInfo.forEach(productsInfo =>{
                    productsInfo.classList.toggle("invisible");
                });
                yourProductsOrder.forEach(productsOrder =>{
                    productsOrder.classList.toggle("invisible");
                });
            }); 
        });  
    }
});