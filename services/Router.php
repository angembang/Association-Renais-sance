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
        case "admin-home":
          $pageController->adminHome();    
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

        case "reset-password-form":
          $authController->resetPasswordForm();  
          break;

        case "check-reset-password":
          $authController->checkResetPassword();  
          break;

        case "membership-register":
          $authController->membershipRegister(); 
          break;

        case "check-membership-register":
          $authController->checkMembershipRegister(); 
          break;

        case "membership-success":
          $pageController->membershipSuccess(); 
          break;

        case "show-membership":
          $pageController->showMemberships(); 
          break;

        case "event-register":
          $authController->eventRegister(); 
          break;

        case "check-event-register":
          $authController->checkEvent(); 
          break;

        case "show-event":
          $pageController->showEvents(); 
          break;

        case "event-registration":
          $authController->eventRegistration(); 
          break;

        case "check-event-registration":
          $authController->checkEventRegistration(); 
          break;
          
        case "event-registration-success":
          $pageController->eventRegistrationSuccess(); 
          break;
        case "show-event-registration":
          $pageController->showEventRegistrations(); 
          break;

        case "news-register":
          $authController->newsRegister(); 
          break;

        case "check-news":
          $authController->checkNews(); 
          break;

        case "news-success":
          $pageController->newsSuccess(); 
          break;

        case "show-news":
          $pageController->showNews(); 
          break;
        
        case "news-detail":
          $pageController->showNewsById(); 
          break;

        case "donation":
          $donationController->showdonationForm(); 
          break;

        case "create-helloasso":
          // Route for displaying a stripe paiement 
          $donationController->createHelloAsso();
          break;

        case "donation-success":
          // Route for displaying a stripe paiement
          $donationController->donationSuccess();
          break;

        case "show-donations":
          $donationController->showDonations();
          break;

        case "error-page":
          $pageController->errorPage();    
          break;

        case "check-contact-form":
          $authController->checkContactForm();    
          break;

        case "about":
          $pageController->about();    
          break;

        case "legacy-policy":
          $pageController->legacyPlolicy();    
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