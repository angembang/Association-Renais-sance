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
    // Instantiate the role manager to retrieve all roles
    $roleManager = new RoleManager();
    $roles = $roleManager->findAll();

    // Render the registration form with necessary data
    $this->render("register.html.twig", [
      "roles" => $roles
    ]);     
  }
    

  /**
   * Validates user registration data and creates a new user account based on provided information.
   */
  public function checkRegister(): void
  {
    try {
      // Check if the request method is POST
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
        // Check if all required fields are present and not empty
        $requiredFields = ["firstName", "lastName", "email", "password", "confirmPassword", "idRole", "csrf-token"];
        foreach ($requiredFields as $field) {
          if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs"]);
            return;
          }
        }

        // Initialize CSRF token manager and validate the token
        $tokenManager = new CSRFTokenManager();
        if (!$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
          $this->renderJson(["success" => false, "message" => "Invalid CSRF token"]);
          return;
        }

        // Check if passwords match
        if ($_POST["password"] !== $_POST["confirmPassword"]) {
          $this->renderJson(["success" => false, "message" => "Les mots de passe ne correspondent pas"]);
          return;
        }

        // Validate password format
        $passwordRegex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/';
        if (!preg_match($passwordRegex, $_POST["password"])) {
          $this->renderJson(["success" => false, "message" => "Le mot de passe doit contenir au moins 8 caractères, un chiffre, une lettre en majuscule, une lettre en minuscule et un caractère spécial."]);
          return;
        }

        // Check if a role is selected and that it is "Admin"
        $roleManager = new RoleManager();
        $role = $roleManager->findRoleById($_POST["idRole"]);

        if (!$role || $role->getName() !== "Admin") {
          $this->renderJson(["success" => false, "message" => "Ce service est réservé aux administrateurs"]);
          return;
        }

        // Check if a user with the given email already exists
        $userManager = new UserManager();
        if ($userManager->findUserByEmail($_POST["email"])) {
          $this->renderJson(["success" => false, "message" => "L'utilisateur existe dejà"]);
          return;
        }

        // Create a new user
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $email = htmlspecialchars($_POST["email"]);
        $hashedPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);

        // Instantiate and save the user
        $user = new User(null, $lastName, $firstName, $email, $_POST["idRole"], $hashedPassword);
        if ($userManager->createUser($user)) {
          $this->render("registerSuccess.html.twig", []);
        } else {
          $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de la création de votre compte."]);
        }

      } else {
        $this->renderJson(["success" => false, "message" => "The form was not submitted via POST method."]);
      }

    } catch (Exception $e) {
      // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Set the HTTP response code for better error handling
      http_response_code($code);

      // Render an error page with the error details
      $this->render("errorPage.html.twig", [
        "code" => $code,
      ]);
      exit();
    }  
  }


  /**
   * Displays the login form with necessary data.
   */
  public function login(): void
  {
    // Render the login form
    $this->render("login.html.twig", []);     
  }


  /**
   * Validates user credentials and performs login if successful.
   */
  public function checkLogin(): void
  { 
    try {
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Check if both email and password fields are set and not empty
        if (isset($_POST["email"]) && !empty($_POST["email"]) &&
        isset($_POST["password"]) && !empty($_POST["password"])) {
  
          // Validate CSRF token
          $tokenManager = new CSRFTokenManager();
          if (!isset($_POST["csrf-token"]) || !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
            $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
            return;
          }
  
          // Instantiate user manager and find user by email
          $userManager = new UserManager();
          $email = htmlspecialchars($_POST["email"]);
          $user = $userManager->findUserByEmail($email);
  
          // Check if user exists
          if ($user) {
            $userPassword = $user->getPassword();
            $password = htmlspecialchars($_POST["password"]);
  
            // Verify the password
            if (password_verify($password, $userPassword)) {
              $userRoleId = $user->getRoleId();
  
              // Retrieve role information
              $roleManager = new RoleManager();
              $role = $roleManager->findRoleById($userRoleId);
  
              if ($role) {
                $roleName = $role->getName();
  
                // Check if the role is Admin
                if ($roleName === "Admin") {
                  // Store the user's identifier in the session
                  $_SESSION["user"] = $user->getId();
                  // Redirect to the admin page
                  $this->render("adminHome.html.twig", []);
                  return;
                } else {
                  $this->renderJson(["success" => false, "message" => "Vous n'avez pas les droits d'accès."]);
                  return;
                }
              } else {
                $this->renderJson(["success" => false, "message" => "Rôle introuvable."]);
                return;
              }
            } else {
              $this->renderJson(["success" => false, "message" => "Mot de passe incorrect."]);
              return;
            }
          } else {
            $this->renderJson(["success" => false, "message" => "L'utilisateur avec cet email n'existe pas."]);
            return;
          }
        } else {
          $this->renderJson(["success" => false, "message" => "Veuillez renseigner tous les champs obligatoires."]);
          return;
        }
      } else {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST."]);
        return;
      }
      
    } catch(Exception $e) {
      // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Set the HTTP response code for better error handling
      http_response_code($code);

      // Render an error page with the error details
      $this->render("errorPage.html.twig", [
        "code" => $code,
      ]);
      exit();
    }   
  }
  

  /**
   * Displays the reset password form Page
   */
  public function resetPasswordForm(): void 
  {
    // Render the reset password page
    $this->render("updateUserForm.html.twig", []);   
  }


  /**
   * Validates the user information and updates the user's password based on the provided email.
   */
  public function checkResetPassword(): void 
  {
    try {
      // Check if the request method is POST
      if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Check if all required fields are present and not empty
        $requiredFields = ["email", "new-password", "confirm-password", "csrf-token"];
        foreach ($requiredFields as $field) {
          if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs"]);
            return;
          }
        }

        // Initialize CSRF token manager and validate the token
        $tokenManager = new CSRFTokenManager();
        if (!isset($_POST["csrf-token"]) || !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
          $this->renderJson(["success" => false, "message" => "Invalid CSRF token"]);
          return;
        }

        // Check if passwords match
        if ($_POST["new-password"] !== $_POST["confirm-password"]) {
          $this->renderJson(["success" => false, "message" => "Les mots de passe ne correspondent pas"]);
          return;
        }

        // Validate password format
        $passwordRegex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/';
        if (!preg_match($passwordRegex, $_POST["new-password"])) {
          $this->renderJson(["success" => false, "message" => "Le mot de passe doit contenir au moins 8 caractères, un chiffre, une lettre en majuscule, une lettre en minuscule et un caractère spécial."]);
          return;
        }

        // Check if a user with the provided email exists
        $userManager = new UserManager();
        $user = $userManager->findUserByEmail($_POST["email"]);
        if (!$user) {
          $this->renderJson(["success" => false, "message" => "Aucun utilisateur trouvé avec cet email"]);
          return;
        }

        // Hash the new password
        $hashedPassword = password_hash($_POST["new-password"], PASSWORD_BCRYPT);

        // Set the user's password 
        $user->setPassword($hashedPassword);

        // Update the user's password using updateUser method
        if ($userManager->updateUser($user)) {
          $this->renderJson(["success" => true, "message" => "Le mot de passe a été mis à jour avec succès"]);
        } else {
          $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de la mise à jour du mot de passe"]);
        }

    } else {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'a pas été soumis via la méthode POST"]);
    }

    } catch (Exception $e) {
      // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Set the HTTP response code for better error handling
      http_response_code($code);

      // Render an error page with the error details
      $this->render("errorPage.html.twig", [
        "code" => $code,
      ]);
      exit();
    }
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

    // Filter out the "Admin" role from the roles list
    $filteredRoles = array_filter($roles, function($role) {
    return strtolower($role->getName()) !== 'admin';
    });

    // Render the membership registration form with the filtered roles
    $this->render("membershipForm.html.twig", [
      "roles" => $filteredRoles
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
            $_SESSION['error_message'] = "Jeton CSRF invalide";
          }

          $membershipManager = new MembershipManager();
          $membership = $membershipManager->findMembershipByEmail($_POST["email"]);
        
          if ($membership !== null) {
            $this->renderJson(["success" => false, "message" => "Un membre avec cet email existe déjà"]);
            return;
          }

          // Initialize HTMLPurifier to sanitize and clean user input from harmful HTML content
          $config = HTMLPurifier_Config::createDefault();
          $purifier = new HTMLPurifier($config);

          // Sanitize input data
          $idRole = htmlspecialchars($_POST["idRole"]);
          $civility = htmlspecialchars($_POST["civility"]);
          $firstName = htmlspecialchars($_POST["firstName"]);
          $lastName = htmlspecialchars($_POST["lastName"]);
          $email = htmlspecialchars($_POST["email"]);
          $phone = htmlspecialchars($_POST["phone"]);
          $address = $purifier->purify($_POST["address"]);
          $postalCode = htmlspecialchars($_POST["postalCode"]);
          $createdAt = date('Y-m-d H:i:s');
          $companyName = isset($_POST["companyName"]) ? $purifier->purify($_POST["companyName"]) : null;
          $logo = null; // Initialize logo as null

          // Retrieve role name
          $roleManager = new RoleManager();
          $role = $roleManager->findRoleById($idRole);
          $roleName = $role->getName();

          // Check if the role is "Partenaire"
          if ($roleName === "Partenaire") {
            if (empty($_FILES['logo']['name'])) {
              $this->renderJson(["success" => false, "message" => "Veuillez ajouter le logo de l'entreprise"]);
              return; 
          }
          $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/avif'];
          $uploadDir = realpath(__DIR__ . '/../uploads/images');

          if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            $this->renderJson(["success" => false, "message" => "Échec de la création du répertoire des images."]);
            return;
          }
          if (!in_array($_FILES['logo']['type'], $allowedImageTypes)) {
            $this->renderJson(["success" => false, "message" => "Le format de l'image n'est pas valide."]);
            return;
          }
          $imagePath = $uploadDir . '/' . basename($_FILES['logo']['name']);
          if (!move_uploaded_file($_FILES['logo']['tmp_name'], $imagePath)) {
            $this->renderJson(["success" => false, "message" => "Erreur lors du téléchargement de l'image."]);
            return;
          } else {
            $logo = '/uploads/images/' . basename($_FILES['logo']['name']);
          }
          } 
          // Create membership object
          $membership = new Membership(null, $civility, $idRole, $firstName, $lastName, $email, $phone, $address, $postalCode, $createdAt, $logo, $companyName, null);
          $createdMembership = $membershipManager->createMembership($membership);

          if ($createdMembership) {
            $this->renderJson([
              "success" => true,
              "roleName" => $roleName // Vous pouvez ajouter d'autres données si nécessaire
          ]);
            
          } else {
            $this->renderJson([
              "success" => false, "message" => "Une erreur s'est produite lors de votre adhésion."]);
          }
        } else {
          $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs"]);
        }
      } else {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST"]);
      }

    } catch (Exception $e) {
        // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Set the HTTP response code for better error handling
      http_response_code($code);

      // Render an error page with the error details
      $this->render("errorPage.html.twig", [
        "code" => $code,
      ]);
      exit(); 
    }
    
  }



  /**
   * Displays the event member registration form with neccessary data
   */
  public function eventRegistration(): void 
  {
    // Retrieve the event ID from the URL
    if (isset($_GET["event_id"])) {
      $eventId = (int)$_GET["event_id"];

      // Validate if the ID corresponds to an existing event in the database
      $eventManager = new EventManager();
      $event = $eventManager->findEventById($eventId);
      if ($event) {
        // Pass the event to the Twig view
        $this->render("eventRegistrationForm.html.twig", [
          "event" => $event
        ]);
      } else {
        // If the event does not exist, display an error
        $this->renderJson(["success" => false, "message" => "Aucun événement trouvé avec cet identifiant"]);
      }
    } else {
      // Redirect or display an error if the ID is not present
      $this->renderJson(["success" => false, "message" => "No identifier found"]);
    }
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
      // check if all required fields are set
      if (!isset($_POST["event_id"]) || empty($_POST["event_id"]) || 
      !isset($_POST["is_member"]) || empty($_POST["is_member"])) {
        $this->renderJson(["success" => false, "message" => "Veuillez remplir les champs requis"]);
        exit();
      }

      // Initialize the CSRFTokenManager and validate the CSRF token
      $tokenManager = new CSRFTokenManager();
      if (isset($_POST["csrf-token"]) && !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
        $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
        exit();
      }

      // Instanciate neccessary managers
      $eventRegistrationManager = new EventRegistrationManager();
      $eventManager = new EventManager();
      $membershipManager = new MembershipManager();

      // Retrieve the event identifier
      $eventId = htmlspecialchars((int)$_POST["event_id"]);
      $event = $eventManager->findEventById($eventId);

      // Check if the event exits
      if (!$event) {
        $this->renderJson(["success" => false, "message" => "Événement non trouvé"]);
        exit();
      }

      // Retrieve the seats available
      $seatsAvailable = $event->getSeatsAvailable();
      $eventRegistrationsEvent = $eventRegistrationManager->findEventRegistrationsByEventId($eventId);
      $countEventRegistrations = count($eventRegistrationsEvent);

      // Check if the are seats availables
      if ($countEventRegistrations >= $seatsAvailable) {
          $this->renderJson(["success" => false, "message" => "L'événement est complet, aucune place disponible"]);
        exit();
      }

      $registrationDate = new DateTime();

      // Retrieve the membership question value
      $isMember = $_POST["is_member"];
      if ($isMember === "true") {
        // Check if the email is provided
        if (!isset($_POST["membership_email"]) || empty($_POST["membership_email"])) {
          $this->renderJson(["success" => false, "message" => "Veuillez renseigner votre adresse email"]);
          exit();
        }

        // Retrieve and sanitize the email
        $email = htmlspecialchars($_POST["membership_email"]);
        $membership = $membershipManager->findMembershipByEmail($email);

        // Check if the membership exists
        if (!$membership) {
          $this->renderJson(["success" => false, "message" => "Le membre avec l'email fourni n'existe pas"]);
          exit();
        }

        // Check if the member with the provided email already exist
        $membershipId = $membership->getId();
        $eventRegistrationMembershipId = $eventRegistrationManager->findEventRegistrationByMembershipId($membershipId);
        if ($eventRegistrationMembershipId) {
          $this->renderJson(["success" => false, "message" => "Le membre avec cet email est déjà enregistré"]);
          exit();
        }

        // Retrieve the first name and last name of the membership
        $membershipFirstName = $membership->getFirstName();
        $membershipLastName = $membership->getLastName();

        // Create an event registration model
        $eventRegistrationModel = new EventRegistration(null, $eventId, $membershipId, $registrationDate, $membershipLastName, $membershipFirstName);
        $eventRegistration = $eventRegistrationManager->createEventRegistration($eventRegistrationModel);

        // check if the event registration is not created
        if (!$eventRegistration) {
          $this->renderJson(["success" => false, "message" => "Échec lors de l'inscription à l'événement"]);
          exit();
        }

        // Retrieve the event by its unique identifier
        $event = $eventManager->findEventById($eventId);
        if (!$event) {
          $message = "Inscription réussie mais malheureusement, nous n'avons pas pu récupérer les détails de l'événement";
          $this->render("errorPage.html.twig", ["message" => $message]);
          exit();
        }

        $eventTitle = $event->getTitle();
        $eventStartDate = $event->getStartDate();
        $eventLocation = $event->getLocation();
        $eventSeatAvailable = $event->getSeatsAvailable();
        $eventOrganizer = $event->getOrganiser();
        $this->renderJson([
          "success" => true,
          "event" => $event,
          "eventTitle" => $eventTitle,
          "eventStartDate" => $eventStartDate,
          "eventLocation" => $eventLocation,
          "eventSeatAvailable" => $eventSeatAvailable,
          "eventOrganizer" => $eventOrganizer
      ]);

      } else {
        // Check if the first name and last name is not provided
        if (!isset($_POST["firstName"]) || empty($_POST["firstName"]) || 
          !isset($_POST["lastName"]) || empty($_POST["lastName"])) {
          $this->renderJson(["success" => false, "message" => "Veuillez renseigner le nom et prénom"]); 
          exit();
        }

        // Retrive and santize the first name and the last name
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);

        // Create an event registration object
        $eventRegistrationModel = new EventRegistration(null, $eventId, null, $registrationDate, $lastName, $firstName);
        $eventRegistration = $eventRegistrationManager->createEventRegistration($eventRegistrationModel);

        // check if the event registration is not persist
        if (!$eventRegistration) {
          $this->renderJson(["success" => false, "message" => "Échec lors de l'inscription à l'événement"]); 
          exit(); 
        }

        // Retrieve the event by its identifier
        $event = $eventManager->findEventById($eventId);
        if (!$event) {
          $message = "Inscription réussie mais malheureusement, nous n'avons pas pu récupérer les détails de l'événement";
          $this->render("errorPage.html.twig", ["message" => $message]);
          exit();
        }

        // Render the succes page
        $eventTitle = $event->getTitle();
        $eventStartDate = $event->getStartDate();
        $eventLocation = $event->getLocation();
        $eventSeatAvailable = $event->getSeatsAvailable();
        $eventOrganizer = $event->getOrganiser();
        $this->renderJson([
          "success" => true,
          "event" => $event,
          "eventTitle" => $eventTitle,
          "eventStartDate" => $eventStartDate,
          "eventLocation" => $eventLocation,
          "eventSeatAvailable" => $eventSeatAvailable,
          "eventOrganizer" => $eventOrganizer
      ]);
      }

    } catch (Exception $e) {
    // log the error for debug
    //error_log("Une erreur s'est produite lors de l'opération: " . $e->getMessage() . " dans " . $e->getFile() . " à la ligne " . $e->getLine());
  
    // Capture the error code for display the error page
    $code = $e->getCode() ? $e->getCode() : 500; // Par défaut à 500 si aucun code n'est fourni
  
    // Défine the http code response for better manage of the error
    http_response_code($code);
  
    // Render the error page with detailes
    $this->render("errorPage.html.twig", ["code" => $code]);
    exit();
    }
  }

  /**
   * Displays the event form
   */
  public function eventRegister(): void 
  {
    // render the the event form   
    $this->render("eventForm.html.twig", []);
  } 


  /**
   * Convert YouTube video URL to embeddable format
   * @param string $url
   * @return string
   */
  public function convertYouTubeUrlToEmbed(string $url): string
  {
    $regex = '/https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/';
    $replace = 'https://www.youtube.com/embed/$1';
    return preg_replace($regex, $replace, $url);
  }


  /**
   * Validates event data and creates a new event  based on provided information.
   */
  public function checkEvent(): void 
  {
    try {
      // Check if the form is not submitted by POST method
      if($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST"]);
      }
      // Initialize an errors array to collect validation issues
      $errors = [];

      // Validate individual fields
      if (!isset($_POST["title"]) || empty(trim($_POST["title"]))) {
        $errors[] = "Le titre est requis.";
      }

      if (!isset($_POST["description"]) || empty(trim($_POST["description"]))) {
        $errors[] = "La description est requise.";
      }

      // Convert dates in objets DateTime
      try {
        $startDate = new DateTime($_POST["startDate"]);
      } catch (Exception $e) {
        $errors[] = "La date de début est invalide.";
      }

      try {
        $endDate = new DateTime($_POST["endDate"]);
      } catch (Exception $e) {
        $errors[] = "La date de fin est invalide.";
      }

      if (!isset($_POST["location"]) || empty(trim($_POST["location"]))) {
        $errors[] = "Le lieu est requis.";
      }

      if (!isset($_POST["organizer"]) || empty(trim($_POST["organizer"]))) {
        $errors[] = "L'organisateur est requis.";
      }

      if (!isset($_POST["seatsAvailable"]) || empty($_POST["seatsAvailable"])) {
        $errors[] = "Le nombre de places disponibles est requis.";
      } elseif (!is_numeric($_POST["seatsAvailable"]) || (int)$_POST["seatsAvailable"] <= 0) {
        $errors[] = "Le nombre de places disponibles doit être un nombre positif.";
      }

      // Check for errors before proceeding
      if (!empty($errors)) {
        $this->renderJson(["success" => false, "errors" => $errors]);
        return;
      }

      // Validate CSRF token
      $tokenManager = new CSRFTokenManager();
      if (isset($_POST["csrf-token"]) && !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
        $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
        return;
      }
      // Initialize HTMLPurifier to sanitize and clean user input from harmful HTML content
      $config = HTMLPurifier_Config::createDefault();
      $purifier = new HTMLPurifier($config);

      // Sanitize input data
      $title = $purifier->purify($_POST["title"]);
      $description = $purifier->purify($_POST["description"]);
      $location = $purifier->purify($_POST["location"]);
      $organizer = $purifier->purify($_POST["organizer"]);
      $seatsAvailable = htmlspecialchars((int) $_POST["seatsAvailable"]);
      $videoUrl = $_POST["video"];
      $image = null;

      // Validate YouTube URL and convert to embed format if provided
      if (!empty($videoUrl)) {
        if (!preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=[\w-]+$/', $videoUrl)) {
          $errors[] = "Le lien YouTube n'est pas valide.";
        } else {
          $videoUrl = $this->convertYouTubeUrlToEmbed($videoUrl);
        }
      }

      // Validate and handle image upload
      $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/avif'];
      $uploadDir = realpath(__DIR__ . '/../uploads');

      if ($uploadDir === false) {
        $errors[] = "Le répertoire de téléchargement n'existe pas.";
      } else {
        $imageDir = $uploadDir . '/images/';
        if (!is_dir($imageDir) && !mkdir($imageDir, 0777, true) && !is_dir($imageDir)) {
          $errors[] = "Échec de la création du répertoire des images.";
        } else {
          if (!empty($_FILES['image']['name'])) {
            if (!in_array($_FILES['image']['type'], $allowedImageTypes)) {
              $errors[] = "Le format de l'image n'est pas valide. Seuls les formats JPEG, PNG et GIF sont acceptés.";
            } else {
              $imagePath = $imageDir . basename($_FILES['image']['name']);
              if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $errors[] = "Erreur lors du téléchargement de l'image.";
              } else {
                $image = '/uploads/images/' . basename($_FILES['image']['name']);
              }
            }
          }
        }
      }

      // Check if any errors occurred during image validation
      if (!empty($errors)) {
        $this->renderJson(["success" => false, "errors" => $errors]);
        return;
      }

      // Instantiate EventManager and create the event
      $eventManager = new EventManager();
      $eventModel = new Event(null, $title, $description, $startDate, $endDate, $location, $organizer, $seatsAvailable, $image, $videoUrl);
      $event = $eventManager->createEvent($eventModel);

      if (!$event) {
        $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de l'enregistrement de l'événement."]);
      } else {
        $this->render("eventSuccess.html.twig", []);
      }

    } catch (Exception $e) {
      //error_log("An error occurred: " . $e->getMessage());
      http_response_code(500);
      $this->render("errorPage.html.twig", ["code" => 500]);
    }
  }


  /**
   * Displays the news form 
   */
  public function newsRegister(): void 
  {
    // render the the news form   
    $this->render("newsForm.html.twig", []);
  }
  
  
  /**
   * Validates news data and creates a new news based on provided information.
   */
  public function checkNews(): void 
  {
    try {
      // Check if the form is submitted via POST
      if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST"]);
        return;
      }

      // Check if the required fields are set
      if (empty($_POST["title"]) ||
      empty($_POST["excerpt"]) ||
      empty($_POST["content"]) ||
      empty($_POST["publicationDate"])) {
        $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs requis"]);
        return;
      }

      // Initialize CSRFTokenManager and validate the CSRF token from the POST request
      $tokenManager = new CSRFTokenManager();
      if (isset($_POST["csrf-token"]) && !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
        $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
        return;
      }
      // Initialize HTMLPurifier to sanitize and clean user input from harmful HTML content
      $config = HTMLPurifier_Config::createDefault();
      $purifier = new HTMLPurifier($config);

      // Retrieve and sanitize the input data
      $title = $purifier->purify($_POST["title"]);
      $excerpt = $purifier->purify($_POST["excerpt"]);
      $content = $purifier->purify($_POST["content"]);
      $publicationDate = htmlspecialchars($_POST["publicationDate"], ENT_QUOTES,  'UTF-8');

      // Convert publication date from string to DateTime
      try {
        $publicationDate = new DateTime($publicationDate);
      } catch (Exception $e) {
          $this->renderJson(["success" => false, "message" => "Format de date invalide"]);
          return;
      }

      $updateDate = null; 

      // Validate file uploads
      $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/avif'];
      $uploadDir = realpath(__DIR__ . '/../uploads');

      if ($uploadDir === false) {
        $this->renderJson(["success" => false, "message" => "Le répertoire de téléchargement n'existe pas."]);
        return;
      }

      $image = null;
      if (!empty($_FILES['image']['name'])) {
        if (!in_array($_FILES['image']['type'], $allowedImageTypes)) {
          $this->renderJson(["success" => false, "message" => "Le format de l'image n'est pas valide."]);
          return;
        } else {
          $imageDir = $uploadDir . '/images/';
          if (!is_dir($imageDir) && !mkdir($imageDir, 0777, true)) {
            $this->renderJson(["success" => false, "message" => "Échec de la création du répertoire des images."]);
            return;
          }
          $imagePath = $imageDir . basename($_FILES['image']['name']);
          if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $this->renderJson(["success" => false, "message" => "Erreur lors du téléchargement de l'image."]);
            return;
          }
          $image = '/uploads/images/' . basename($_FILES['image']['name']);
        }
      }
      $videoUrl = $_POST["video"];
      if (!empty($videoUrl)) {
        if (!preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=[\w-]+$/', $videoUrl)) {
          $errors[] = "Le lien YouTube n'est pas valide.";
        } else {
          $videoUrl = $this->convertYouTubeUrlToEmbed($videoUrl);
        }
      }
      // Create a News object
      $newsModel = new News(
        null, // id will be auto-generated
        $title,
        $content,
        $image,
        $videoUrl,
        $publicationDate,
        $updateDate,
        $excerpt
      );

      // Instantiate the news manager and create the news
      $newsManager = new NewsManager();
      $news = $newsManager->createNews($newsModel);

      if (!$news) {
        $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de l'enregistrement de l'actualité."]);
        return;
      }

      // Render the success page
      $this->render("newsSuccess.html.twig", []);

    } catch (Exception $e) {
      // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Set the HTTP response code for better error handling
      http_response_code($code);

      // Render an error page with the error details
      $this->render("errorPage.html.twig", [
        "code" => $code,
      ]);
      exit();
    }
    
  }


  /**
   * Validates contact form data and creates a new message based on provided information.
   */
  public function checkContactForm(): void 
  {
    try {
      // Check if the request method is POST
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
        // Check if all required fields are present and not empty
        $requiredFields = ["name", "email", "subject", "message", "csrf-token"];
        foreach ($requiredFields as $field) {
          if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs"]);
            return;
          }
        }

        // Initialize CSRF token manager and validate the token
        $tokenManager = new CSRFTokenManager();
        if (!$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
          $this->renderJson(["success" => false, "message" => "Invalid CSRF token"]);
          return;
        }
        
        // Initialize HTMLPurifier to sanitize and clean user input from harmful HTML content
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        // Retrieve and sanitize the input data
        $subject = $purifier->purify($_POST["subject"]);
        $message = $purifier->purify($_POST["message"]);

        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);

        $status = "Nouveau";
        $createdAt = new DateTime();

        // Create a message model
        $messageModel = new Message(null, $name, $email, $subject, $message, $status, $createdAt);

        // Instantiate the message manager to create a Message object
        $messageManager = new MessageManager();
        $messageCreated = $messageManager->createMessage( $messageModel);

        if(!$messageCreated) {
          $this->renderJson(["success" => false, "message" => "Echec lors de l'envoi de message."]);
        } else {
          $this->render("messageSuccess.html.twig", []);
        }

      } else {
        $this->renderJson(["success" => false, "message" => "The form was not submitted via POST method."]);
      }

    } catch (Exception $e) {
      // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error code for the error page
      $code = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is provided;
     
      // Set the HTTP response code for better error handling
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
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION = [];
    session_destroy();
    header("Location: /home"); 
    exit();
  }
  
}