<?php

/**
 * Class Router
 * Handles the routing of requests.
 */
class Router
{
  /**
   * Handles the incoming request based on the provided $_GET parameters.
   *
   * @param array $get The associative array of $_GET parameters.
   * 
   * @return void
   */
  public function handleRequest(array $get): void
  {
    // Instantiate the necessary controllers
    $authController = new AuthController();
    $pageController = new PageController();
    $donationController = new DonationController();
        
    // Check if a route is provided
    if(isset($get["route"])) {
      // Switch statement for routing
      switch($get["route"]) {
        case "home":
          $pageController->home();    
          break;

        case "inscription":
          $authController->register(); 
          break;
                    
        case "checkRegister":
          $authController->checkRegister();
          break;
                
        case "connexion":
          $authController->login();
          break;
                    
        case "checkLogin":
          $authController->checkLogin(); 
          break;

        case "membership-register":
          $authController->membershipRegister(); 
          break;

        case "check-membership-register":
          $authController->checkMembershipRegister(); 
          break;

        case "event-registration":
          $authController->eventRegistration(); 
          break;

        case "check-event-registration":
          $authController->checkEventRegistration(); 
          break;

        case "donation":
          $donationController->showdonationForm(); 
          break;

        case "create-paiement-stripe":
          // Route for displaying a stripe paiement 
          $donationController->createStripe();
          break;

        case "donation-success":
          // Route for displaying a stripe paiement
          $donationController->donationSuccess();
          break;

        case "donation-success-clean":
          $donationController->donationSuccessClean();
          break;

        case "show-donations":
          $donationController->showDonations();
          break;

        case "error-page":
          $pageController->errorPage();    
          break;

        case "contact":
          $pageController->contactPage();    
          break;
                              
        case "logout":
          $authController->logout();    
          break;
                    
        default:
        $pageController->home();  
          break;
      } 
            
    } else {
      // Route is not provided/ render the home page
      $pageController->home(); 
      die;
      } 
  }
        
}