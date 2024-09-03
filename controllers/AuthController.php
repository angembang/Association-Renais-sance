<?php

/**
 * Controller responsible for handling user registration and login.
 */
class AuthController extends AbstractController
{
  /**
   * Displays the registration form with necessary data.
   */
  public function register(): void
  {     
  }
    

  /**
   * Validates user registration data and creates a new user account based on provided information.
   */
  public function checkRegister(): void
  {    
  }


  /**
   * Displays the login form with necessary data.
   */
  public function login(): void
  {     
  }


  /**
   * Validates user credentials and performs login if successful.
   */
  public function checkLogin(): void
  {    
  }


  /**
   * Displays the membership form with necessary data.
   */
  public function membershipRegister(): void
  { 
    // Instantiate the role manager
    $roleManager = new RoleManager();
    
    // Get all roles from the database
    $roles = $roleManager->findAll();

    // Render the membership registration form with necessary data
    $this->render("membershipForm.html.twig", [
      "roles" => $roles
    ]);    
  }


  /**
   * Validates membership registration data and creates a new membership based on provided information.
   */
  public function checkMembershipRegister(): void
  {
    try {
      // Check if the request method is POST and all required form fields are set and not empty
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if((isset($_POST["civility"]) && !empty($_POST["civility"])) && 
        (isset($_POST["idRole"]) && !empty($_POST["idRole"])) &&
        (isset($_POST["firstName"]) && !empty($_POST["firstName"])) &&
        (isset($_POST["lastName"]) && !empty($_POST["lastName"])) &&
        (isset($_POST["email"]) && !empty($_POST["email"])) &&
        (isset($_POST["phone"]) && !empty($_POST["phone"])) &&
        (isset($_POST["address"]) && !empty($_POST["address"])) && 
        (isset($_POST["postalCode"]) && !empty($_POST["postalCode"]))) {
          // Initialize CSRFTokenManager and validate the CSRF token from the POST request
          $tokenManager = new CSRFTokenManager();
          if (!$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
            $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
            return;
          }

          $membershipManager = new MembershipManager();
          $membership = $membershipManager->findMembershipByEmail($_POST["email"]);
        
          if ($membership !== null) {
            $this->renderJson(["success" => false, "message" => "Un membre avec cet email existe déjà"]);
            return;
          }

          $idRole = htmlspecialchars($_POST["idRole"]);
          $civility = htmlspecialchars($_POST["civility"]);
          $firstName = htmlspecialchars($_POST["firstName"]);
          $lastName = htmlspecialchars($_POST["lastName"]);
          $email = htmlspecialchars($_POST["email"]);
          $phone = htmlspecialchars($_POST["phone"]);
          $address = htmlspecialchars($_POST["address"]);
          $postalCode = htmlspecialchars($_POST["postalCode"]);
          $createdAt = date('Y-m-d H:i:s');

          $membership = new Membership(null, $civility, $idRole, $firstName, $lastName, $email, $phone, $address, $postalCode, $createdAt);
          $createdMembership = $membershipManager->createMembership($membership);

          if ($createdMembership) {
            $this->renderJson(["success" => true, "message" => "Membre enregistré avec succès"]);
          } else {
              $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de votre adhésion."]);
          }

        } else {
          $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs"]);
        }
      } else {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST"]);
      }

    } catch (Exception $e) {
        $this->renderJson(["success" => false, "message" => $e->getMessage()]); 
    }
    
  }
     


  /**
   * Logs out the user.
   */
  public function logout() : void
  {
  }
  
}