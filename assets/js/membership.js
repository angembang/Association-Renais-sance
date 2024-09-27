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
});