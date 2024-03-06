document.addEventListener('DOMContentLoaded', function(){

    function panierPayement(){
        const vertical = document.querySelector("#lookPanier");
        const recapPanierUser = document.querySelector(".recapPanierUser");

        const recapPanierUserImg = document.querySelector(".recapPanierUserImg");
        const recapPanierUserDivCol = document.querySelector(".recapPanierUserDivCol");
        const recapPanierUserTotal = document.querySelector(".recapPanierUser-total");
        console.log(vertical);
        const basketData = localStorage.getItem("basket");
            const basket = JSON.parse(basketData);

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

    const totalPrice = document.getElementById("totalPrice");
    totalPrice.textContent = "Total " +  getTotalPrice() + "€";
    
    const formLivraison = document.querySelector(".formLivraison");
    const basketData = localStorage.getItem("basket");
    const basket = JSON.parse(basketData);

    basket.forEach(product => {
        const inputProductHidden = document.createElement("input");
        inputProductHidden.type = "hidden";
        inputProductHidden.id = "products" + product.id;
        inputProductHidden.name = "products" + product.id;
        inputProductHidden.value = product.id ;
        formLivraison.appendChild(inputProductHidden);

        const inputQuantityHidden = document.createElement("input");
        inputQuantityHidden.type = "hidden";
        inputQuantityHidden.id = "quantity" + product.id;
        inputQuantityHidden.name = "quantity" + product.id;
        inputQuantityHidden.value = product.quantity ;
        formLivraison.appendChild(inputQuantityHidden);

        const inputPrices = document.createElement("input");
        inputPrices.type = "hidden";
        inputPrices.id = "prices" + product.id;
        inputPrices.name = "prices" + product.id;
        inputPrices.value = product.prices ;
        formLivraison.appendChild(inputPrices);

        
        const inputPricesHidden = document.createElement("input");
        inputPricesHidden.type = "hidden";
        inputPricesHidden.id = "totalPrices" + product.id;
        inputPricesHidden.name = "totalPrices" + product.id;
        inputPricesHidden.value = getTotalPrice() ;
        formLivraison.appendChild(inputPricesHidden);

        const inputProductLength = document.createElement("input");
        inputProductLength.type = "hidden";
        inputProductLength.id = "productLength";
        inputProductLength.name = "productLength";
        inputProductLength.value = basket.length ;
        formLivraison.appendChild(inputProductLength);
    });


    function stripePay(){
        /* global Stripe */
        /* global stripe */
        /* global fetch */
        /* global URLSearchParams */

        const stripe = Stripe('pk_test_51OivsfHV0B5kGx6vbcguaX73gt5xPBlXbyMPy6h6HCBNe3riPyx6U4uL6LltbTrg1lhmx0Www3uQLnk2a0P63k5500E1eNzwGx');

        let amount;
        
        const montant = document.querySelector('#totalPrices1');
      

        amount = parseInt(montant.value);
        //console.log(amount);
        if(amount >= 1){
            initialize();
        }

        let elements;

        checkStatus();

        document
        .querySelector("#payment-form")
        .addEventListener("submit", handleSubmit);

        // Fetches a payment intent and captures the client secret
        async function initialize() {
        const { clientSecret } = await fetch("index.php?route=stripePay", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ amount }),
        }).then((r) => r.json());

        elements = stripe.elements({ clientSecret });

        const paymentElementOptions = {
            layout: "tabs",
        };

        const paymentElement = elements.create("payment", paymentElementOptions);
        paymentElement.mount("#payment-element");
        
        
        }

        async function handleSubmit(e) {
        e.preventDefault();
        setLoading(true);

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
            return_url: 'index.php?route=checkPayement'
            },
        });
        
        //EN
        // This point will only be reached if there is an immediate error when
        // confirming the payment. Otherwise, your customer will be redirected to
        // your `return_url`. For some payment methods like iDEAL, your customer will
        // be redirected to an intermediate site first to authorize the payment, then
        // redirected to the `return_url`.
        
        //FR
        // Ce point ne sera atteint que s’il y a une erreur immédiate lorsque
        // confirmant le paiement. Sinon, votre client sera redirigé vers
        // votre «return_url». Pour certains modes de paiement comme iDEAL, votre client
        // être redirigé vers un site intermédiaire d’abord pour autoriser le paiement, puis
        // redirigé vers le “ return_url ”.
        
        if (error.type === "card_error" || error.type === "validation_error") {
            showMessage(error.message);
        } else {
            showMessage("An unexpected error occurred.");
        }

        setLoading(false);
        }

        // Fetches the payment intent status after payment submission
        async function checkStatus() {
        const clientSecret = new URLSearchParams(window.location.search).get(
            "payment_intent_client_secret"
        );

        if (!clientSecret) {
            return;
        }

        const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

        switch (paymentIntent.status) {
            case "succeeded":
            showMessage("Payment succeeded!" );
            break;
            case "processing":
            showMessage("Your payment is processing.");
            break;
            case "requires_payment_method":
            showMessage("Your payment was not successful, please try again.");
            break;
            default:
            showMessage("Something went wrong.");
            break;
        }
        }

        // ------- UI helpers -------

        function showMessage(messageText) {
        const messageContainer = document.querySelector("#payment-message");

        messageContainer.classList.remove("hidden");
        messageContainer.textContent = messageText;

        setTimeout(function () {
            messageContainer.classList.add("hidden");
            messageContainer.textContent = "";
        }, 4000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
        if (isLoading) {
            // Disable the button and show a spinner
            document.querySelector("#submit").disabled = true;
            document.querySelector("#spinner").classList.remove("hidden");
            document.querySelector("#button-text").classList.add("hidden");
        } else {
            document.querySelector("#submit").disabled = false;
            document.querySelector("#spinner").classList.add("hidden");
            document.querySelector("#button-text").classList.remove("hidden");
        }
        }
    }

    stripePay();
    panierPayement();

    
});


