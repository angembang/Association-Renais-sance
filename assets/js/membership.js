document.getElementById("idRole").addEventListener("change", function() {
  const logoField = document.getElementById("logoField");
  const companyNameField = document.getElementById("companyNameField");
  const selectedRole = this.options[this.selectedIndex].text; 
  
  if (selectedRole === "Partenaire") {
    companyNameField.style.display = "flex";
    logoField.style.display = "flex"; 
  } else {
    logoField.style.display = "none"; 
    companyNameField.style.display = "none";
  }


  // Show/Hide payment question for "Bénévole" or "Membre adhérant"
  /*const paymentQuestionField = document.querySelector('.paymentQuestionField');
  if (selectedRole === "Bénévole" || selectedRole === "Membre adhérent") {
    paymentQuestionField.style.display = "flex"; // Show payment question
    document.getElementById("payNow").required = true; 
  } else {
    paymentQuestionField.style.display = "none"; // Hide payment question
    document.getElementById("payNow").required = false;
    document.getElementById("payment-option").style.display = "none"; // Hide membership fee if not applicable
  }
  // Check the current selection of payment question to adjust the "Adhérer" button
  adjustSubmitButtonDisplay();
});

// Function to toggle the visibility of the membership fee field
function toggleMembershipFee() {
  const paymentSelect = document.getElementById("payNow");
  const membershipFeeField = document.getElementById("payment-option");

  if (paymentSelect.value === "yes") {
    membershipFeeField.style.display = "flex"; // Show membership fee
    document.getElementById("submitButton").style.display = "none"; // Hide "Adhérer" button
  } else {
    membershipFeeField.style.display = "none"; // Hide membership fee
    adjustSubmitButtonDisplay(); // Adjust "Adhérer" button visibility
  }
}

// Function to adjust the visibility of the "Adhérer" button based on payment question
function adjustSubmitButtonDisplay() {
  const paymentSelect = document.getElementById("payNow");
  const submitButton = document.getElementById("submitButton");

  if (paymentSelect.value === "yes") {
    submitButton.style.display = "none"; // Hide "Adhérer" button
  } else {
    submitButton.style.display = "block"; // Show "Adhérer" button
  }
}


// Vérification avant la soumission
document.getElementById("registerForm").addEventListener("submit", function(event) {
    const paymentSelect = document.getElementById("payNow");
    if (paymentSelect.required && paymentSelect.style.display !== "none" && paymentSelect.value === "") {
        event.preventDefault(); // Empêcher la soumission si le champ requis est vide
        alert("Veuillez sélectionner une option de paiement.");
    }*/
});
