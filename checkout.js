document.addEventListener("DOMContentLoaded", function () {
  let amount; // Variable to store the entered amount
  const amountInput = document.getElementById("montant-personnalise");
  const submitButton = document.getElementById("submit");

  // Event listener for changes in the amount input
  amountInput.addEventListener("change", function (event) {
    amount = parseFloat(event.target.value); // Parse the entered value as a float
    //console.log("Amount entered:", amount);
    submitButton.disabled = amount < 1; // Disable the button if amount is less than 1
  });

    /**
     * Initializes the payment process by preparing and sending the payload to the server.
     * 
     * This function collects form data, prepares a payload for the HelloAsso API,
     * sends the request, and handles the response.
     * 
     * @return {Promise<void>}
     */
    async function initialize() {
    const form = document.querySelector("#payment-form");
    

    // Retrieve form informations
    const anonymous = form.querySelector("select[name='anonymous']").value === "true";
    const isMember = form.querySelector("select[name='is_member']").value === "true";
    const membershipEmail = form.querySelector("input[name='membership_email']").value || null;
    const firstName = form.querySelector("input[name='firstName']").value || null;
    const lastName = form.querySelector("input[name='lastName']").value || null;
    const message = form.querySelector("textarea[name='message']").value || null;

     // Convert the amount to cents
    const totalAmount = amount * 100; // Total amount in cents
    const initialAmount = totalAmount; // Amount to be paid immediately

    // Prepare the payload for the HelloAsso API
    const payload = {
      totalAmount: amount * 100,
      initialAmount: amount * 100,
      itemName: "Don à l'Association Renais'sance",
      backUrl: "http://www.asso-renais-sance/index.php?route=home",
      errorUrl: "http://www.asso-renais-sance/index.php?route=error",
      returnUrl: "http://www.asso-renais-sance/index.php?route=donation-success",
      containsDonation: true,
      payer: {
        firstName: firstName,
        lastName: lastName,
        email: membershipEmail,
      },
      metadata: {
        message: message
      }
    };

    // Send the request to the server
    const response = await fetch("./index.php?route=create-helloasso", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const responseData = await response.text();
    
    try {
      const parsedData = JSON.parse(responseData); // Parse the response data
        
      if (parsedData.error) {
        showMessage(parsedData.error); // Display error message if present
        return;
      }

      // Handle redirection if successful
      const { redirectUrl } = parsedData;
      window.location.href = redirectUrl; // Redirect to the URL provided by HelloAsso
    } catch (error) {
      //console.error("Failed to parse JSON:", error);
      showMessage("Une erreur est survenue lors de la communication avec le serveur."); // Error message
    }
  }
  // Event listener for form submission
  const form = document.getElementById("payment-form");
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
    //console.log("Form submitted with amount:", amount);
      if (amount >= 1) {
        //console.log("Initializing payment...");
        initialize(); // Start the payment process
      } else {
        showMessage("Le montant doit être supérieur ou égal à 1€."); // Display error message if amount is invalid
      }
    });

    /**
     * Displays a message to the user.
     * 
     * @param {string} messageText - The message to display.
     */
    function showMessage(messageText) {
      const messageContainer = document.querySelector("#payment-message");
      messageContainer.classList.remove("hidden"); // Show the message container
      messageContainer.textContent = messageText; // Set the message text

      // Hide the message after a timeout
      setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageContainer.textContent = "";
      }, 4000);
    }
});