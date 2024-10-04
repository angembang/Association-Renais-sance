document.addEventListener("DOMContentLoaded", function () {
    showRegisterError();

    function showRegisterError() {
        const membershipRegisterForm = document.getElementById("registerForm");
        const errorModal = document.getElementById("errorModal");
        const errorMessageElement = document.getElementById("error-message");
        const closeBtnError = document.querySelector(".close-btn");

        membershipRegisterForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const formData = new FormData(membershipRegisterForm);
    
            const options = {
                method: "POST",
                body: formData
            };
    
            fetch("index.php?route=check-membership-register", options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Réponse du serveur non valide');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    membershipRegisterForm.reset();
                    
                    // Stockez le rôle dans localStorage
                    localStorage.setItem("roleName", data.roleName);
            
                    window.location.href = "index.php?route=membership-success";
                } else {
                    errorMessageElement.textContent = data.message || "Une erreur s'est produite lors de l'adhésion.";
                    errorModal.style.display = "block"; // Affiche la modale
                }
            })
            .catch(error => {
                //console.error("Erreur lors de l'adhésion", error);
                errorMessageElement.textContent = "Une erreur s'est produite lors de l'adhésion.";
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