//sauvegarder le pannier dans le Stockage local pour y accéder console->appli->stockage local
function saveProductTicket(productTicket){
    localStorage.setItem("ticket", JSON.stringify(productTicket));
}

//recuperer le Stockage local du pannier
function getProductTicket(){
    let productTicket = localStorage.getItem("ticket");
    if(productTicket === null){
        return [];
    } else{
        return JSON.parse(productTicket);
    }
}

//ajouter un produit au panier
function addProductTicket(product){
    let productTicket = getProductTicket();
    let foundProduct = productTicket.find(p => p.id == product.id);
    if(foundProduct != undefined){
        foundProduct.quantity++;
    } else{
        product.quantity = 1;
        productTicket.push(product);
    } 
    saveProductTicket(productTicket);
}

function removeFromProductTicket(product){
    let productTicket = getProductTicket();
    productTicket = productTicket.filter(p => p.id != product.id);
    saveProductTicket(productTicket);
}

function changeQuantityTicket(product, quantity) {
    let productTicket = getProductTicket();
    let foundProduct = productTicket.find(p => p.id === product.id);
    if (foundProduct != undefined) {
        foundProduct.quantity += quantity;
        if(foundProduct.quantity <=0){
            removeFromProductTicket(foundProduct);
        } else{
            saveProductTicket(productTicket);
        }   
    }
}

function getNumberTicket(){
    let productTicket = getProductTicket();
    let number = 0;
    for(let product of productTicket){
        number += product.quantity;
    }
    return number;
}


function getTotalPriceTicket(){
    let productTicket = getProductTicket();
    let total = 0;
    for(let product of productTicket){
        total += product.quantity * product.prices;
    }
    return total;
}



document.addEventListener("DOMContentLoaded", function() {
    const ticketContainers = document.querySelectorAll(".tickets");

    function selectTribune() {
        ticketContainers.forEach(ticketsList => {
            let isSelectTicketListCreated = false; // Variable pour vérifier si la liste de sélection de billets a déjà été créée

            ticketsList.addEventListener("click", function() {
                // Retirer la liste de sélection de billets des autres éléments .tickets
                ticketContainers.forEach(container => {
                    if (container !== ticketsList) {
                        const selectTicketList = container.querySelector(".selectTicket");
                        if(selectTicketList){
                            container.removeChild(selectTicketList);
                            localStorage.removeItem("ticket");

                            isSelectTicketListCreated = false; // Remettre à false après suppression
                        }
                    } 
                    
                });
                
                // Si la liste de sélection de billets n'a pas déjà été créée, la créer
                if (!isSelectTicketListCreated) {
                    // Création de l'élément <ul> pour la sélection du billet
                    const selectTicketList = document.createElement("ul");
                    selectTicketList.classList.add("selectTicket");
                    // Création du premier <li> contenant les boutons "-" et "+"
                    const firstListItem = document.createElement("li");
                    // Création du bouton "-"
                    const minusButton = document.createElement("button");
                    minusButton.classList.add("btn", "btnTicketMoins");
                    minusButton.textContent = "-";
                    firstListItem.appendChild(minusButton);
                    // Création du paragraphe pour afficher la quantité
                    const quantityParagraph = document.createElement("p");
                    quantityParagraph.classList.add("numberTicket");
                    quantityParagraph.textContent = getProductTicket().length > 0 ? getProductTicket()[0].quantity : 0;
                    firstListItem.appendChild(quantityParagraph);
                    // Création du bouton "+"
                    const plusButton = document.createElement("button");
                    plusButton.classList.add("btn", "btnTicketPlus");
                    plusButton.textContent = "+";
                    firstListItem.appendChild(plusButton);
                    // Ajout du premier <li> à la liste de sélection de billets
                    selectTicketList.appendChild(firstListItem);
                    // Création du deuxième <li> contenant le formulaire pour continuer
                    const secondListItem = document.createElement("li");
                    // Création du formulaire
                    const formElement = document.createElement("form");
                    formElement.setAttribute("action", "index.php?route=payementTicket");
                    formElement.setAttribute("method", "POST");
                    // Création du bouton "Continuer" dans le formulaire
                    const continueButton = document.createElement("button");
                    continueButton.setAttribute("type", "submit");
                    continueButton.classList.add("addCartTicket", "btn");
                    continueButton.textContent = "Continuer";
                    formElement.appendChild(continueButton);
                    // Ajout du formulaire au deuxième <li>
                    secondListItem.appendChild(formElement);
                    // Ajout du deuxième <li> à la liste de sélection de billets
                    selectTicketList.appendChild(secondListItem);
                    // Ajout de la liste de sélection de billets à l'élément .tickets cliqué
                    ticketsList.appendChild(selectTicketList);
                    // Mettre à jour la variable pour indiquer que la liste de sélection de billets a été créée
                    isSelectTicketListCreated = true;

                    // Accéder aux boutons d'ajout et de soustraction à l'intérieur de la boucle
                    const addQuantity = selectTicketList.querySelector(".btnTicketPlus");
                    const removeQuantity = selectTicketList.querySelector(".btnTicketMoins");
                    const textTicketQuantity = selectTicketList.querySelector(".numberTicket");
                    
                    const tribune = ticketsList.querySelector(".tribune");
                    btnContinuer = ticketsList.querySelector(".addCartTicket");
                    const match = document.querySelector("section:nth-child(2) > div > h2");
                    const matchId = document.querySelector(".matchId");
                    const ticketPrice = document.querySelector(".ticketPrice");

                    // Appel de la fonction asynchrone addLocalStorageTicket
                    addLocalStorageTicket(addQuantity, removeQuantity, textTicketQuantity, tribune, btnContinuer, match, matchId, ticketPrice);
                }
            });
        });  
    }

    // Fonction asynchrone addLocalStorageTicket
    async function addLocalStorageTicket(addQuantity, removeQuantity, textTicketQuantity, tribune, btnContinuer, match, matchId, ticketPrice) {
        if (addQuantity) {
            // Désactiver le bouton "Continuer" par défaut
            btnContinuer.disabled = true;

            // Activer le bouton "Continuer" si la quantité dans le localStorage est supérieure à 0
            getProductTicket().length > 0 ? btnContinuer.disabled = false : 0;

            addQuantity.addEventListener("click", function() {
                let currentValue = parseInt(textTicketQuantity.textContent);
                currentValue++;
                textTicketQuantity.textContent = currentValue;

                if (currentValue === 6) {
                    addQuantity.style.display = "none";
                }

                if (currentValue > 0) {
                    btnContinuer.disabled = false; // Activer le bouton Continuer si currentValue > 0
                } 

                let product = {
                    id: tribune.dataset.id,
                    //userEmail : 
                    match: match.textContent,
                    matchId: matchId.textContent,
                    tribune: tribune.textContent,
                    prices: ticketPrice.textContent,
                };

                let productInCart = getProductTicket().find(p => p.id === product.id);
                if (productInCart) {
                    changeQuantityTicket({id: product.id}, 1);
                } else {
                    addProductTicket(product);
                }
            });
        }
        if (removeQuantity) {
            removeQuantity.addEventListener("click", function() {
                let currentValue = parseInt(textTicketQuantity.textContent);
                if (currentValue > 0) {
                    currentValue--;
                    textTicketQuantity.textContent = currentValue;
                    changeQuantityTicket({id: tribune.dataset.id}, -1);
                }

                if (currentValue === 0) {
                    btnContinuer.disabled = true; 
                    localStorage.removeItem("ticket");
                }

                if (currentValue < 6) {
                    addQuantity.style.display = "block";
                }
            });
        }
    }

    const recapTicketUser = document.querySelector(".recapTicketUser");
    const recapTicketUserCol = document.querySelector(".recapTicketUserDivCol");
    const recapTicketUserTotal = document.querySelector(".recapTicket-total");
    const lookTicket = document.querySelector("#lookTicket");
    const formTicketStripe = document.querySelector(".formTicketStripe");

    const ticketData = localStorage.getItem("ticket");
        if(ticketData){
            const tickets = JSON.parse(ticketData);
            if(tickets){
                tickets.forEach(ticket =>{
                    if(lookTicket){
                        lookTicket.addEventListener("click", function(){
                            recapTicketUser.classList.toggle("invisible");

                            recapTicketUserCol.innerHTML = "";
                            recapTicketUserTotal.innerHTML ="";
                            
                            const titleMatch = document.createElement("h5");
                            titleMatch.textContent = ticket.match ;
                            recapTicketUserCol.appendChild(titleMatch);

                            const ptribune = document.createElement("p");
                            ptribune.textContent = ticket.tribune ;
                            recapTicketUserCol.appendChild(ptribune);

                            const pQuantityPrice = document.createElement("p");
                            pQuantityPrice.textContent = ticket.prices + "€ x Qté " + ticket.quantity ;
                            recapTicketUserCol.appendChild(pQuantityPrice);

                            const ptotalPrices = document.createElement("p");
                            ptotalPrices.textContent ="TotalTTC : " + getTotalPriceTicket() + " €";
                            recapTicketUserTotal.appendChild(ptotalPrices);

                            
                        });
                    }
                    if(formTicketStripe){
                        let ticketUser = {
                            "match":ticket.match,
                            "tribune":ticket.tribune,
                            "quantity":parseInt(ticket.quantity),
                            "prices":parseInt(ticket.prices*100),
                            "totalPrices":getTotalPriceTicket()
                        };
                        
                        let tableauJSON = JSON.stringify(ticketUser);
                        const arrayTicket = document.createElement("input");
    
                        arrayTicket.type = "hidden";
                        arrayTicket.name= "arrayTicket[]";
                        arrayTicket.value= tableauJSON;
                        formTicketStripe.appendChild(arrayTicket);
                        
                    }
                });
            }
        }
    
                    

    // Appel de la fonction selectTribune
    selectTribune();
});








