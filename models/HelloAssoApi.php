<?php

class HelloAssoAPI
{
  // Private variables to store API credentials and organization slug
  private $clientId = 'dddddddddddddddddddddddddddddddddddd';
  private $clientSecret = 'rrrrrrrrrrrrrrrrrrrrrrrrr'; 
  private $organizationSlug = 'association-renais-sance'; 
  private $apiKey;

  // Constructor method to initialize the API key
  public function __construct() {
    $this->apiKey = $this->getAccessToken();
  }

  /**
   * Retrieves the access token from HelloAsso API using client credentials.
   * 
   * @return string|null Returns the access token or null if an error occurs.
   */
  private function getAccessToken() 
  {
    $url = "https://api.helloasso-sandbox.com/oauth2/token"; 
  
    // Data to send in the POST request to obtain the access token
    $data = [
      'client_id' => $this->clientId,
      'client_secret' => $this->clientSecret,
      'grant_type' => 'client_credentials'
    ];
  
    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/x-www-form-urlencoded"
    ]);
  
    // Execute cURL request and retrieve the response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  
    // Log for debugging the response
    error_log("Response from token request: $response");
    error_log("HTTP Code: $httpCode");

    // Check for errors in the response
    if ($httpCode !== 200) {
      error_log("Erreur d'authentification: " . $response);
    }
  
    // Decode the response to get the access token
    $responseData = json_decode($response, true);
    return $responseData['access_token'] ?? null; // Retourne le token d'accès
  }

  
    /**
     * Test function to display the obtained access token.
     */
    public function testAccessToken() {
    $token = $this->getAccessToken();
    echo $token ? "Token obtenu: $token" : "Erreur lors de l'obtention du token.";
  }

    
    /**
     * Processes the payment through HelloAsso API.
     * 
     * This method reads the input data, prepares the payment payload,
     * sends the request to HelloAsso, and returns the redirect URL.
     * 
     * @return void
     */
    public function paymentHelloAsso(): void 
    {
      // Retrieve JSON input data from the request
      $input = json_decode(file_get_contents('php://input'), true);
      $amount = $input['totalAmount'] ?? 0;
      $firstName = $input['payer']['firstName'] ?? null;
      $lastName = $input['payer']['lastName'] ?? null;
      $membershipEmail = $input['payer']['email'] ?? null;

      // Prepare the payload for the payment request
      $payload = [
        'totalAmount' => $amount,
        'initialAmount' => $amount,
        'itemName' => "Don à l'Association Renais'sance",
        'backUrl' => "https://asso-renais-sance/index.php?route=home",
        'errorUrl' => "https://asso-renais-sance/index.php?route=error",
        'returnUrl' => "https://asso-renais-sance/index.php?route=donation-success",
        'containsDonation' => true,
        'payer' => [
          'firstName' => $firstName,
          'lastName' => $lastName,
          'email' => $membershipEmail,
        ],
      ];

      // Initialize cURL session for the payment request
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://api.helloasso-sandbox.com/v5/organizations/$this->organizationSlug/checkout-intents");
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $this->apiKey",
        "Content-Type: application/json"
      ]);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Récupérer la réponse
      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

       // Log response for debugging
       error_log("Response from HelloAsso: $response");
      error_log("HTTP Code: $httpCode");

      header('Content-Type: application/json');

      // Decode the response to an associative array
      $responseData = json_decode($response, true);

      // Check for errors in the payment request
      if ($httpCode !== 200) {
        error_log("Erreur lors de la connexion à HelloAsso. Code: $httpCode, Réponse: $response");
        echo json_encode(['error' => "Erreur lors de la connexion à HelloAsso. Code: $httpCode"]);
        exit;
      }

       // Check if the response contains an ID and a redirect URL
      if (!isset($responseData['id']) || !isset($responseData['redirectUrl'])) {
        echo json_encode(['error' => 'Redirection URL non trouvée']);
        exit;
      }

      // Return the redirect URL if everything is okay
      echo json_encode(['redirectUrl' => $responseData['redirectUrl']]);
      exit;
    }
}