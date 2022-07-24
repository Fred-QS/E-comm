// Create an instance of the Stripe object with your publishable API key
let stripe = Stripe(pk),
    checkoutButton = document.querySelector('#checkout-button');

checkoutButton.addEventListener('click', function(){
    fetch('/ma-commande/create-session/' + orderRef,{
        method: 'POST'
    })
        .then(function(response){
            return response.json();
        })
        .then(function(session){
            if (session.error === 'order') {
                window.location.replace(orderReplace);
            } else {
                return stripe.redirectToCheckout({sessionId: session.id});
            }
        })
        .then(function(result){
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function (error){
            console.error("Error:", error)
        })
});