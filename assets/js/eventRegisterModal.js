document.addEventListener("DOMContentLoaded", function () {
    showEventRegistrationError();

    function showEventRegistrationError() {
        const eventRegistrationForm = document.getElementById("eventRegistrationForm");
        const errorModal = document.getElementById("errorModal");
        const errorMessageElement = document.getElementById("error-message");
        const closeBtnError = document.querySelector(".close-btn");


      eventRegistrationForm.addEventListener("submit", function(event) {
          event.preventDefault();
          const formData = new FormData(eventRegistrationForm);
  
          const options = {
              method: "POST",
              body: formData
          };
  
          fetch("index.php?route=check-event-registration", options)
          .then(response => {
              if (!response.ok) {
                  throw new Error('Réponse du serveur non valide');
              }
              return response.json();
          })
          .then(data => {
              if (data && data.success) {
                  eventRegistrationForm.reset();
                  
                   // Stock event details in localStorage
                   localStorage.setItem("eventDetails", JSON.stringify({
                    title: data.eventTitle,
                    startDate: data.eventStartDate,
                    location: data.eventLocation,
                    seatsAvailable: data.eventSeatAvailable,
                    organizer: data.eventOrganizer
                }));
          
                  window.location.href = "index.php?route=event-registration-success";
              } else {
                errorMessageElement.textContent = data.message || "Une erreur s'est produite lors de l'inscription à l'événement.";
                errorModal.style.display = "block"; // Affiche la modale
              }
          })
          .catch(error => {
            //console.error("Erreur lors de l'adhésion", error);
            errorMessageElement.textContent = "Une erreur s'est produite lors de l'inscription à l'événement.";
            errorModal.style.display = "block"; // Affiche la modale
          });
      });
  
      closeBtnError.addEventListener("click", function() {
        errorModal.style.display = "none"; // Masque la modale
    });
  
    window.onclick = function(event) {
      if (event.target === errorModal) {
          errorModal.style.display = "none"; // Masque la modale si on clique en dehors
      }
  };
}
});