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
   * Displays the event member registration form with neccessary data
   */
  public function eventRegistration(): void 
  {
    // render the the event registration form with neccessary data   
    $this->render("eventRegistrationForm.html.twig", []);
  } 


  /**
   * Validates event registration data and creates a new event regitration based on provided information.
   */
  public function checkEventRegistration(): void 
  {
    try {
      // Check if the form is not submitted by POST method
      if($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST"]);
      }
      // Check if all required form fields are set and not empty
      if((!isset($_POST["event_id"]) || empty($_POST["event_id"])) &&
      (!isset($_POST["is_member"]) || empty($_POST["is_member"]))) {
        $this->renderJson(["success" => false, "message" => "Veuillez remplir les champs requis"]);
      }
      // Initialize CSRFTokenManager and validate the CSRF token from the POST request
      $tokenManager = new CSRFTokenManager();
      if (isset($_POST["csrf-token"]) && !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
        $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
      }
      // Instantiate the event registration, event and membership managers 
      $evenRegistrationManager = new EventRegistrationManager();
      $eventManager = new EventManager();
      $membershipManager = new MembershipManager();
      // Retrieve the event identifier
      $enventId = htmlspecialchars($_POST["event_id"]);

      $registrationDate = new DateTime();

      // Retrieve the is membership value
      $isMember = $_POST["is_member"];
      // Check if is member is true for more validations
      if($isMember === "true") {
        // Check if the email is provided
        if((!isset($_POST["email"]) || empty($_POST["email"]))) {
          $this->renderJson(["success" => false, "message" => "Veuillez renseigner votre adresse email"]);
        }
        // Retrieve and sanitize the email
        $email = htmlspecialchars($_POST["email"]);
        // Retrieve the membership by email
        $membership = $membershipManager->findMembershipByEmail($email);
        // Check if the membership is null
        if(!$membership) {
          $this->renderJson(["success" => false, "message" => "Le membre avec l'email fourni n'existe pas"]);
        }
        // Retrieve the unique identifier, the first name and last name of the membership
        $membershipId = $membership->getId();
        $membershipFirstName = $membership->getFirstName();
        $membershipLastName = $membership->getLastName();

        // Create a event model registration 
        $eventRegistrationModel = new EventRegistration(null, $enventId, $membershipId, $registrationDate, $membershipLastName, $membershipFirstName);

        $eventRegistration = $evenRegistrationManager->createEventRegistration($eventRegistrationModel);
        // Check if the event registration is not created
        if(!$eventRegistration) {
          $this->renderJson(["success" => false, "message" => "Echec lors de l'inscription à l'évenement"]);
          exit();
        }
        // Retrieve the event by its unique identifier
        $event = $eventManager->findEventById($enventId);
        if(!$event) {
          $message = "Inscription reussie mais malheureusement, nous n'avons pas pu récupérer les details de l'événement";
          $this->render("errorPage.html.twig", [
            "message" => $message
          ]);
        }
        // Render the success event registration page with event details
        $this->render("eventRegistrationSuccess.html.twig", [
          'event' => $event
        ]);

      } else {
          // Check if the first name and last name are not set
          if((!isset($_POST["firstName"]) || empty($_POST["firstName"])) &&
          (!isset($_POST["lastName"]) || empty($_POST["lastName"]))) {
            $this->renderJson(["success" => false, "message" => "Veuillez renseigner le nom et prénom"]); 
          }
          // Retrieve and sanitize the first name and last name input data
          $firstName = htmlspecialchars($_POST["firstName"]);
          $lastName = htmlspecialchars($_POST["lastName"]);

          // Create a event register object
          $eventRegistrationModel = new EventRegistration(null, $enventId, null, $registrationDate, $lastName, $firstName);

          // Persists the event registration to the database
          $eventRegistration = $evenRegistrationManager->createEventRegistration($eventRegistrationModel);

          // Check if the event registration is not persisted
          if(!$eventRegistration) {
            $this->renderJson(["success" => false, "message" => "Echec lors de l'inscription à l'évenement"]); 
            exit(); 
          }
          // Retrieve the event by its unique identifier
          $event = $eventManager->findEventById($enventId);
          if(!$event) {
            $message = "Inscription reussie mais malheureusement, nous n'avons pas pu récupérer les details de l'événement";
            $this->render("errorPage.html.twig", [
              "message" => $message
            ]);
          }
          // Render the success event registration page with event details
          $this->render("eventRegistrationSuccess.html.twig", [
            'event' => $event
          ]);
      }
      
    } catch (Exception $e) {
      // Log the error details for debugging
      error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Optionally set the HTTP response code for better error handling
      http_response_code($code);

      // Render an error page with the error details
      $this->render("errorPage.html.twig", [
        "code" => $code,
      ]);
      exit();
    }

  }
     


  /**
   * Logs out the user. 
   */
  public function logout() : void
  {
  }
  
}