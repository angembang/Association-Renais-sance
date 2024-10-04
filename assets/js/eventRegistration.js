document.addEventListener("DOMContentLoaded", function () {
  console.log("Le DOM est chargé."); // Test initial pour vérifier le chargement
  const isMemberField = document.getElementById("is_member");
  const membershipEmailField = document.getElementById("membership-email-field");
  const firstNameField = document.getElementById("first-name-field");
  const lastNameField = document.getElementById("last-name-field");
  const eventId = document.getElementById("event_id");
  
  // Function to update form fields based on the selected membership status
  function updateFormFields() {
    const isMember = isMemberField.value === 'true';
    //console.log("Statut de membre sélectionné :", isMemberField.value); // Debug

    if (isMemberField.value === 'true') {
      //console.log("L'utilisateur est membre, affichage du champ email."); // Debug
      membershipEmailField.style.display = 'block';
      firstNameField.style.display = 'none';
      lastNameField.style.display = 'none';
    } else if (isMemberField.value === 'false') {
      //console.log("L'utilisateur n'est pas membre, affichage des champs nom et prénom."); // Debug
      membershipEmailField.style.display = 'none';
      firstNameField.style.display = 'block';
      lastNameField.style.display = 'block';
    }
  }

  // Initial update of the form when the page loads
  updateFormFields();

  // Update form fields when the membership status changes
  isMemberField.addEventListener('change', updateFormFields);

  
});