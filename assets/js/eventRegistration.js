document.addEventListener("DOMContentLoades", function () {
  document.querySelector('form').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    try {
      const response = await fetch(this.action, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success === false) {
        showModal(result.message); // Show error message in modal
      } else {
        // Redirect to success page or handle successful registration
        window.location.href = '/path-to-success';
      }

   } catch (error) {
      console.error('An error occurred:', error);
    }
  });

  // Function to display error messages in the modal
  function showModal(message) {
    const modal = document.getElementById('registerMessageModal');
    const modalContent = document.getElementById('registerModalContent');
    modalContent.innerText = message;
    modal.style.display = 'block';
  }

  function closeModal() {
    const modal = document.getElementById('registerMessageModal');
    modal.style.display = 'none';
  }
  
})