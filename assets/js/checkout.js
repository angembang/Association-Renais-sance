document.addEventListener("DOMContentLoaded", function () {
  // Initialize Stripe with the public key
  const stripe = Stripe('pk_test_51PTNZZJ3Pl4ECRsy750C4Y5fcqwGJ4cK0ABwGoBYrckWnNi7WP6TdzuBlXlyAzD8zjYdPYFIgmR0S3tbgK3eeJVy00ek3o2pds');

  let amount;

  // Select the amount input field
  let amountInput = document.getElementById("montant-personnalise");

  // Add an event listener to update the amount on user input
  amountInput.addEventListener("change", function (event)
  {
    // Update the amount value
    amount = parseFloat(event.target.value);
    console.log("Amount entered:", amount); // Debugging
  
    // Check if the amount is equal to or greater than 1€
    if(amount >= 1)
    {
      console.log("Initializing payment..."); // Debugging
      initialize();
    }
  });


  let elements;

  // Check the status of the payment intent when the page loads
  checkStatus();

  // Add an event listener to handle form submission
  document
    .querySelector("#payment-form")
    .addEventListener("submit", handleSubmit);

  // Initialize Stripe Elements and create a payment intent
  async function initialize() {
    // Fetch the client secret from the server by creating a payment intent
    const { clientSecret } = await fetch("./index.php?route=create-paiement-stripe", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ amount}), // Send the amount to the server
    }).then((r) => r.json());

    // Initialize Stripe Elements with the client secret
    elements = stripe.elements({ clientSecret });

    const paymentElementOptions = {
      layout: "tabs", // Define the layout for the payment element
    };

    // Create and mount the payment element
    const paymentElement = elements.create("payment", paymentElementOptions);
    paymentElement.mount("#payment-element");
  
    // Enable the submit button and update its text with the donation amount
    const buttonSubmit = document.querySelector('form#payment-form button#submit');
    buttonSubmit.disabled = false;
    buttonSubmit.querySelector("#button-text").textContent = "Faire un don de " + amount + " €";
    console.log("Button activated:", buttonSubmit.disabled); // Should log 'false'
  }

  // Handle the form submission and confirm the payment
  async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    const form = document.querySelector("#payment-form");
    const anonymous = form.querySelector("select[name='anonymous']").value === "true";
    const isMember = form.querySelector("select[name='is_member']").value === "true";
    const membershipEmail = form.querySelector("input[name='membership_email']").value || null;
    const firstName = form.querySelector("input[name='firstName']").value || null;
    const lastName = form.querySelector("input[name='lastName']").value || null;
    const message = form.querySelector("textarea[name='message']").value || null;
    const amount = form.querySelector("select[name='montant-personnalise']").value;

    const { error } = await stripe.confirmPayment({
      elements,
      confirmParams: {
        // Redirect to this URL after the payment is confirmed 
        return_url: `http://localhost/index.php?route=donation-success&anonymous=${encodeURIComponent(anonymous)}&is_member=${encodeURIComponent(isMember)}&membership_email=${encodeURIComponent(membershipEmail)}&firstName=${encodeURIComponent(firstName)}&lastName=${encodeURIComponent(lastName)}&message=${encodeURIComponent(message)}&montant-personnalise=${encodeURIComponent(amount)}`,
      },
    });

  
    // Handle any errors that occur during payment confirmation
    if (error.type === "card_error" || error.type === "validation_error") {
    showMessage(error.message);
    } else {
      showMessage("An unexpected error occurred.");
    }

    setLoading(false); // Remove loading state
  }

  // Check the payment intent status after the payment is submitted
  async function checkStatus() {
    // Retrieve the client secret from the URL parameters
    const clientSecret = new URLSearchParams(window.location.search).get(
      "payment_intent_client_secret"
    );

    if (!clientSecret) {
      return; // If there's no client secret, exit the function
    }

    // Retrieve the payment intent from Stripe using the client secret
    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

    // Handle different payment intent statuses
    switch (paymentIntent.status) {
      case "succeeded":
        showMessage("Payment succeeded!"); // Payment was successful
        break;
      case "processing":
        showMessage("Your payment is processing."); // Payment is still being processed
        break;
      case "requires_payment_method":
        showMessage("Your payment was not successful, please try again."); // Payment failed, user needs to try again
        break;
      default:
        showMessage("Something went wrong."); // An unexpected status was returned
        break;
    }
  }

  // ------- UI helpers -------

  // Display a message to the user
  function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");

    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;

    // Hide the message after 4 seconds
    setTimeout(function () {
      messageContainer.classList.add("hidden");
      messageContainer.textContent = "";
    }, 4000);
  }

  // Show a spinner and disable the submit button during payment submission
  function setLoading(isLoading) {
    if (isLoading) {
      // Disable the button and show a spinner
      document.querySelector("#submit").disabled = true;
      document.querySelector("#spinner").classList.remove("hidden");
      document.querySelector("#button-text").classList.add("hidden");
    } else {
      // Enable the button and hide the spinner
      document.querySelector("#submit").disabled = false;
      document.querySelector("#spinner").classList.add("hidden");
      document.querySelector("#button-text").classList.remove("hidden");
    }
  }

})

