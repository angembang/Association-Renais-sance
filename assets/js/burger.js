document.addEventListener("DOMContentLoaded", function() {
  // Select the burger icon, nav ul elements, the first li of the nav ul and the h1
  const burgerIcon = document.getElementById("menu-icon");
  const navMenu = document.querySelector("ul.display-none");
  const closeMenuItem = navMenu.querySelector("li:first-child");
  const pageTitle = document.querySelector("#menu h1");

  // Add event listener to the burger button
  burgerIcon.addEventListener("click", function(event) {
    // Remove the 'display-none' class to show the menu
    navMenu.classList.remove("display-none");
    navMenu.classList.add("none");
    navMenu.classList.add("open");
    burgerIcon.style.display = "none";
    pageTitle.classList.add("small-title");
  })


  // Close the menu when on click on the first li of the nav list 
  closeMenuItem.addEventListener("click", function() {
    navMenu.classList.remove("open");
    navMenu.classList.add("display-none");
    navMenu.classList.remove("none"); 
    burgerIcon.style.display = "block";
    pageTitle.classList.remove("small-title");
  });

})