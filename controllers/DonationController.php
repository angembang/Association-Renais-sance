<?php

/**
 * Class DonationController
 *
 * This controller handles the actions related to Stripe payment processing for donations.
 */
class DonationController extends AbstractController
{
  /**
   * Creates a Stripe payment intent and returns the client secret.
   * This method retrieves the payment amount from the JSON payload, creates a payment intent
   * with the specified amount and currency using the Stripe API, and then returns the client secret
   * required for completing the payment on the client side.
   * 
   * @return void
   */
  public function createStripe(): void 
  {
    // Retrieve the Stripe secret key from .env file
    $key = $_ENV['API_KEY'];

    // Create an instance of stripe client
    $stripe = new \Stripe\StripeClient($key);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    try {
      // Retrieve the raw JSON input from the request body
      $jsonStr = file_get_contents('php://input');
      // Decode the JSON input into an associative array/object
      $jsonObj = json_decode($jsonStr);

      // Create a Stripe payment intent with the amount and currency provided
      $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $jsonObj->amount * 100, // The amount in cents (e.g., 10.00 EUR is represented as 1000 cents)
        'currency' => 'eur', // The currency for the payment (euro)
      ]);

      // Prepare the response containing the client secret
      $output = [
        'clientSecret' => $paymentIntent->client_secret
      ];
      // Send the client secret as a JSON response
      echo json_encode($output);

    } catch (\Stripe\Exception\ApiErrorException $e) {
      // Handle any errors that occur during the API request to Stripe
      http_response_code(500); // Set HTTP status code to 500 (Internal Server Error)
      echo json_encode(['error' => $e->getMessage()]); // Send the error message as a JSON response
    }
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
      $paymentIntentId = $_GET["payment_intent"] ?? null;
      $anonymous = isset($_GET["anonymous"]) ? filter_var($_GET["anonymous"], FILTER_VALIDATE_BOOLEAN) : false;
      $isMember = isset($_GET["is_member"]) ? filter_var($_GET["is_member"], FILTER_VALIDATE_BOOLEAN) : false;
      $membershipEmail  = isset($_GET["membership_email"]) ? urldecode($_GET["membership_email"]) : null;
      $firstName = isset($_GET["firstName"]) ? urldecode($_GET["firstName"]) : null;
      $lastName = isset($_GET["lastName"]) ? urldecode($_GET["lastName"]) : null;
      $message = isset($_GET["message"]) ? urldecode($_GET["message"]) : null;
      $amount = isset($_GET["montant-personnalise"]) ? urldecode($_GET["montant-personnalise"]) : null;

      // Validate amount
      if (!is_numeric($amount)) {
        throw new Exception("Invalid amount.");
      }

      // Store the retrieved parameters in the session
      $_SESSION['donation-form'] = [
        "Payment Intent ID" => $paymentIntentId,
        "anonymous" => $anonymous,
        "is_member" => $isMember,
        "membershipEmail" => $membershipEmail,
        "firstName" => $firstName,
        "lastName" => $lastName,
        "message" => $message,
        "amount" => $amount
      ];

      // Validate paymentIntentId
      if (!$paymentIntentId) {
        throw new Exception("Missing payment intent ID.");
      }

       // Validate email format only if the user is a member and provides an email
       if ($isMember && $membershipEmail && !filter_var($membershipEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
      }

      // Check the payment status
      try {
        $stripe = new \Stripe\StripeClient($_ENV["API_KEY"]);
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);
      } catch (\Stripe\Exception\ApiErrorException $e) {
        throw new Exception("Error with payment: " . $e->getMessage());
      }

      if ($paymentIntent->status !== "succeeded") {
        throw new Exception("Payment did not succeed.");
      }

      // Create donation and persist it to the database
      $createdAt = new DateTime();
      $amount = (float)$amount;
      $anonymous = ($anonymous === true) ? 1 : 0;

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
      unset($_SESSION['donation']);

      // Redirect to a clean URL
      $this->render("donationSuccess.html.twig", []);
      exit();
    } catch (Exception $e) {
      error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
      header("Location: index.php?route=error-page");
      exit();
    }
  }


  /*
   * cleans the url parameters
   * 
   */
  public function donationSuccessClean(): void 
  {
    // render the success donation page 
    $this->render("donationSuccess.html.twig", []);
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
      dump($donations);
      dump($members);
      //die(); // Stopper l'exécution pour voir le contenu

      // Render the donation page with neccessary data
      $this->render("donation.html.twig", [
        "donations" => $donations,
        "members" => $members
      ]);

    } catch (Exception $e) {
      // Log the error details for debugging
      error_log("An error occurred during the operation: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(). $e->getCode());
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