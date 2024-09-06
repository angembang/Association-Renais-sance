document.addEventListener("DOMContentLoaded", function () {
  const anonymousSelect = document.getElementById('anonymous');
  const membershipField = document.getElementById('membership-field');
  const membershipEmailField = document.getElementById('membership-email-field');
  const firstNameField = document.getElementById('first-name-field');
  const lastNameField = document.getElementById('last-name-field');
  const messageField = document.getElementById('message-field');

  // Function to update form fields based on the donation type
  function updateFormFields() {
    const isAnonymous = anonymousSelect.value === 'true';
    const isMemberSelect = document.getElementById('is_member');

    // Show only message and amount fields if anonymous
    if (isAnonymous) {
      membershipField.style.display = 'none';
      membershipEmailField.style.display = 'none';
      firstNameField.style.display = 'none';
      lastNameField.style.display = 'none';
      messageField.style.display = 'block';
    } else {
      // Show membership field if not anonymous
      membershipField.style.display = 'block';
      messageField.style.display = 'block';

      // Show email or name fields based on membership status
      if (isMemberSelect) {
        isMemberSelect.addEventListener('change', function () {
          const isMember = isMemberSelect.value === 'true';

          if (isMember) {
            membershipEmailField.style.display = 'block';
            firstNameField.style.display = 'none';
            lastNameField.style.display = 'none';
          } else {
            membershipEmailField.style.display = 'none';
            firstNameField.style.display = 'block';
            lastNameField.style.display = 'block';
          }
        });
      }
    }
  }

  // Initial form update
  updateFormFields();

  // Update form fields when the donation type changes
  anonymousSelect.addEventListener('change', updateFormFields);
});