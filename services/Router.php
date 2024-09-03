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
        
    // Check if a route is provided
    if(isset($get["route"])) {
      // Switch statement for routing
      switch($get["route"]) {
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
                    
        case "logout":
          $authController->logout();    
          break;
                    
        default:
        $authController->membershipRegister();  
          break;
      } 
            
    } else {
      // Route is not provided/ render the home page
      $authController->membershipRegister(); 
      die;
      } 
  }
        
}