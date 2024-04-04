document.addEventListener('DOMContentLoaded', function(){
    const lookMyOrder = document.querySelector(".myOrder > h2");
    const yourTickets = document.querySelector(".yourTickets");
    const yourProducts = document.querySelector(".yourProducts");
    const connectionAndSecurity = document.querySelector(".connectionAndSecurity > h2");
    const addressforDelivery = document.querySelector(".addressforDelivery > h2");
    const contact = document.querySelector(".contact > h2");

    function toggleInvisible(item){
        item.classList.toggle("invisible");
    }

    if(addressforDelivery){
        addressforDelivery.addEventListener("click", function(){
            const formDelivery = document.querySelector(".formDelivery");
            const userAddress = document.querySelector(".userAddress");
            const changeAddress = document.querySelector(".changeAddress");
            const formForChangeAddress = document.querySelector(".formForChangeAddress");
            if(formDelivery){
                toggleInvisible(formDelivery);
            }
            if(userAddress){
                toggleInvisible(userAddress);
                toggleInvisible(changeAddress);
                changeAddress.addEventListener("click", function(){
                    toggleInvisible(formForChangeAddress);
                });    
            }
        });
    }

    if(connectionAndSecurity){
        const changeName = document.querySelector(".changeName");
        const changeEmail = document.querySelector(".changeEmail");
        const changePassword = document.querySelector(".changePassword");
        connectionAndSecurity.addEventListener("click", function() {
            toggleInvisible(changeName);
            toggleInvisible(changePassword);
            toggleInvisible(changeEmail);
            if(changeName){
                const changeNameH3 = document.querySelector(".changeName > h3");
                const formChangeName = document.querySelector(".formChangeName");
                changeNameH3.addEventListener("click", function(){
                    toggleInvisible(formChangeName);
                });
            }
            if(changePassword){
                const changePasswordH3 = document.querySelector(".changePassword > h3");
                const formChangerPassword = document.querySelector(".formChangerPassword");

                changePasswordH3.addEventListener("click", function(){
                    toggleInvisible(formChangerPassword);
                });
            }
            if(changeEmail){
                const changeEmailH3 = document.querySelector(".changeEmail > h3");
                const formChangerEmail = document.querySelector(".formChangerEmail");
                
                changeEmailH3.addEventListener("click", function(){
                    toggleInvisible(formChangerEmail);
                });
            }
        });
    }

    if(contact){
        const coordinate = document.querySelector(".coordinate");
        contact.addEventListener("click", function(){
            toggleInvisible(coordinate);
        });
    }
   
    
    if(lookMyOrder){
        lookMyOrder.addEventListener("click", function(){
            toggleInvisible(yourProducts);
            toggleInvisible(yourTickets);
            
            yourTickets.addEventListener("click", function(){
                const yourTicketsOrder = document.querySelectorAll(".yourTickets-order");
                yourTicketsOrder.forEach(orderTickets =>{
                    toggleInvisible(orderTickets);
                });
                const yourTicketsInfo = document.querySelectorAll(".yourTickets-info");
                yourTicketsInfo.forEach(ticket =>{
                    toggleInvisible(ticket);
                });
            });
            yourProducts.addEventListener("click", function(){
                const yourProductsInfo = document.querySelectorAll(".yourProducts-info");
                yourProductsInfo.forEach(productsInfo =>{
                    toggleInvisible(productsInfo);
                });
                const yourProductsOrder = document.querySelectorAll(".yourProducts-order");
                yourProductsOrder.forEach(productsOrder =>{
                    toggleInvisible(productsOrder);
                });
            }); 
        });  
    }

});