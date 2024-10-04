<?php

/**
 * Class DonationController
 *
 * This controller handles the actions related to Stripe payment processing for donations.
 */
class DonationController extends AbstractController
{
  /**
   * Initializes a payment request with HelloAsso and returns the redirect URL.
   * This method retrieves the payment details from the JSON payload, prepares
   * a request to the HelloAsso API to create a checkout intent, and returns
   * the URL for the contributor to complete the payment.
   * 
   * @return void
   */
  public function createHelloAsso(): void 
  {
    $helloAssoAPI = new HelloAssoAPI();
    $helloAssoAPI->paymentHelloAsso();
  }

  
  /*
   * Displays the donation form
   * 
   */
  public function showdonationForm(): void 
  {
    $montants = range(5, 5000, 5);
    // Render the donation form 
    $this->render("donationForm.html.twig", [
      "montants" => $montants
    ]);  
  }


  /**
   * Handle the success of a donation payment process.
   *
   * This function retrieves the necessary parameters from the GET request, validates them,
   * checks the status of the payment via Stripe's API, and then creates a donation entry in the database.
   * The donation data includes information such as membership, payment amount, and optional messages.
   * Upon success, it clears session data and redirects to a success page. In case of failure, 
   * it logs the error and redirects to an error page.
   *
   * @throws Exception If the payment fails, the donation cannot be created, or there are validation issues.
   *
   * @return void
   */
  public function donationSuccess(): void 
  {
    try {
      // Retrieve the redirection parameters
      $checkoutIntentId = $_GET["checkoutIntentId"] ?? null; // ID for the HelloAsso checkout
      $code = $_GET["code"] ?? null; // Payment status code
      $amount = $_SESSION['donation-form']['amount'] ?? null; // Amount should be stored in the session
        $anonymous = $_SESSION['donation-form']['anonymous'] ?? false; // Anonymous flag
        $isMember = $_SESSION['donation-form']['is_member'] ?? false; // Member flag
        $membershipEmail = $_SESSION['donation-form']['membershipEmail'] ?? null; // Membership email
        $firstName = $_SESSION['donation-form']['firstName'] ?? null; // Payer's first name
        $lastName = $_SESSION['donation-form']['lastName'] ?? null; // Payer's last name
        $message = $_SESSION['donation-form']['message'] ?? null; // Donation message

      // Validate payment status
      if ($code !== "succeeded") {
        throw new Exception("Payment was not successful.");
      }

      // Validate the amount
      if (!is_numeric($amount)) {
        throw new Exception("Invalid amount.");
      }
      // If the user is a member and provides an email, validate the email format
      if ($isMember && $membershipEmail && !filter_var($membershipEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
      }

      // Create donation and persist it to the database
      $createdAt = new DateTime();
      $amount = (float)$amount; // Convert amount to float
      $anonymous = ($anonymous === true) ? 1 : 0; // Convert boolean to int

      // If the user is a member and provides an email, find membership by email
      $membershipId = null;
      if ($isMember && $membershipEmail) {
        $membershipManager = new MembershipManager();
        $membership = $membershipManager->findMembershipByEmail(htmlspecialchars($membershipEmail));
        $membershipId = $membership ? $membership->getId() : null;
      }
      // Create donation object
      $donationData = new Donation(null, $membershipId, $amount, $createdAt, htmlspecialchars($message), $anonymous, htmlspecialchars($lastName), htmlspecialchars($firstName));
      $donationManager = new DonationManager();
      $donation = $donationManager->createDonation($donationData);

      if (!$donation) {
        throw new Exception("Failed to create donation.");
      }

      // Clear session data for security reasons
      unset($_SESSION['donation-form']);

      // Redirect to a clean URL
      $this->render("donationSuccess.html.twig", []);
      exit();
    } catch (Exception $e) {
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
     $this->render("errorPage.html.twig", [
      "code" => $e->getCode()
     ]);
      exit();
    }
  }


  /** 
   * Fetches and displays all donations.
   *
   * This method retrieves all the donation records using the DonationManager.
   * If no donations are found, it throws an exception with an appropriate error code.
   * In case of any error during the process, it catches the exception, logs the error details,
   * sets an HTTP response code, and renders an error page with the corresponding message.
   *
   * @throws Exception if no donations are found or any other error occurs. 
   */
  public function showDonations(): void 
  {
    try {
      // Instanviate neccessary managers
      $donationManager = new DonationManager();
      $memberManager = new MembershipManager();
      // retrieve all donations
      $donations = $donationManager->findAll();

      // Check if donations are not empty
      if(!$donations) {
        throw new Exception("No donation found", 404);
      }

      // Fetch member data if donations have membership IDs
      $members = [];
      foreach ($donations as $donation) {
        $mId =(int) $donation->getMembershipId();
        if ($mId) {
            $members[$mId] = $memberManager->findMembershipById($mId);
        }
      }
      // Debug : var_dump pour vérifier les données
      //dump($donations);
      //dump($members);
      //die(); // Stopper l'exécution pour voir le contenu

      // Render the donation page with neccessary data
      $this->render("donation.html.twig", [
        "donations" => $donations,
        "members" => $members
      ]);

    } catch (Exception $e) {
      // Log the error details for debugging
      //error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
      // Capture the error message and code for the error page
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
    
    
}