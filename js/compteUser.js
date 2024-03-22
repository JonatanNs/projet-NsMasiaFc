document.addEventListener('DOMContentLoaded', function(){
    const mesCommandesh2i = document.querySelector(".mesCommandes > h2 > i");
    const vosTickets = document.querySelector(".vosTickets");
    const vosProduits = document.querySelector(".vosProduits");
    const vosTicketsOrder = document.querySelectorAll(".vosTickets-order");
    const vosTicketsInfo = document.querySelectorAll(".vosTickets-info");
    const vosProduitsOrder = document.querySelectorAll(".vosProduits-order");
    const vosProduitsInfo = document.querySelectorAll(".vosProduits-info");

    if(mesCommandesh2i){
        mesCommandesh2i.addEventListener("click", function(){
                vosProduits.classList.toggle("invisible");
                vosTickets.classList.toggle("invisible");
            
                vosTickets.addEventListener("click", function(){
                    vosTicketsOrder.forEach(orderTickets =>{
                        orderTickets.classList.toggle("invisible");
                    });
                    vosTicketsInfo.forEach(ticket =>{
                        ticket.classList.toggle("invisible");
                    });
                });

                vosProduits.addEventListener("click", function(){
                    vosProduitsInfo.forEach(produitsInfo =>{
                        produitsInfo.classList.toggle("invisible");
                    });
                    vosProduitsOrder.forEach(produitsOrder =>{
                        produitsOrder.classList.toggle("invisible");
                    });
                });
           
            });  
        
    }
});