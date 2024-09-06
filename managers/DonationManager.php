<?php
use PHPUnit\Framework\Constraint\StringStartsWith;

/**
 * Manages the retrieval and persistence of Donation object in the platform
 */
class DonationManager extends AbstractManager
{
  /**
   * creates a new donation and persists it in the database.
   * 
   * @param Donation $donation The donation object to be created.
   * 
   * @return Donation The created donation object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createDonation(Donation $donation): Donation
  {
    try {
      // Prepare the SQL query to insert a new donation into the database
      $query = $this->db->prepare("INSERT INTO donations ( membership_id, amount, donation_date, message, anonymous, last_name, first_name) 
      VALUES (:membership_id, :amount, :donation_date, :message, :anonymous, :last_name, :first_name)");

      // Convert DateTime object to a string in the correct format
      $formattedDate = $donation->getDonationDate()->format('Y-m-d H:i:s');

      // Bind the parameters with their values.
      $parameters = [
        ":membership_id" => $donation->getMembershipId(),
        ":amount" => $donation->getAmount(),
        ":donation_date" => $formattedDate, // Formatted date as string
        ":message" => $donation->getMessage(),
        ":anonymous" => $donation->isAnonymous() ? 1 : 0,
        ":last_name" => $donation->getLastName(),
        ":first_name" =>$donation->getFirstName()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $donationId = $this->db->lastInsertId();

      // Set the identifier for the created donation
      $donation->setId($donationId);

      // Return the created donation object
      return $donation;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a donation" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a donation");
    }
  }
  


  /**
   * Retrieves an donation by its unique identifier
   * 
   * @param int $donationId The unique identifier of the donation
   * 
   * @return Donation|null The retrieved donation. Null if not found
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findDonationById(int $donationId): ?Donation
  {
    try {
      // Prepare the SQL query to retrieve the donation by its unique identifier
      $query = $this->db->prepare("SELECT * FROM donations WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $donationId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the donation data from the database
      $donationData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if donation is found
      if ($donationData) {
        return $this->hydrateDonation($donationData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a donation:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a donation");
    }     
  }


  /**
   * Retrieves all donations
   *
   * @return array|null The array of donation or null if no donation is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all donations into the database
      $query = $this->db->prepare("SELECT * FROM donations ORDER BY donation_date DESC");

      // Execute the query
      $query->execute();

      // Fetch donation data from the database
      $donationsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if donations data is not empty
      if($donationsData) {
        $donations = [];
        // Loop through each donations data
        foreach($donationsData as $donationData) {
          // Convertir la chaîne de date en objet DateTime
          $donationDate = new DateTime($donationData["donation_date"]);

          // Instantiate an donation for each donation data
          $donation = new Donation(
            $donationData["id"],
            $donationData["membership_id"],
            $donationData["amount"],
            $donationDate,
            $donationData["message"],
            $donationData["anonymous"],
            $donationData["last_name"],
            $donationData["first_name"]
          );
          // Add the instantiated donation object to the donation array
          $donations[] = $donation;
        }
        // Return the array of the donation objects
        return $donations;
      }
      // No donation is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find donation:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find donation");
    }  
  }


  /**
   * Updates an donation in the database
   * 
   * @param Donation $donation The donation to be updated.
   * 
   * @return Donation|null The donation updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateDonation(Donation $donation): ?Donation
  {
    try {
      // Prepare the SQL query to update an donation.
      $query = $this->db->prepare("UPDATE donations SET 
      user_id = :user_id,
      amount = :amount,
      donation_date = :donation_date,
      message = :message,
      anonymous = :anonymous,
      last_name = :last_name,
      first_name = :first_name
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $donation->getId(),
        ":membership_id" => $donation->getMembershipId(),
        ":amount" => $donation->getAmount(),
        ":donation_date" => $donation->getDonationDate(),
        ":message" => $donation->getMessage(),
        ":anonymous" => $donation->isAnonymous(),
        ":last_name" => $donation->getLastName(),
        ":first_namee" => $donation->getFirstName()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $donation;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the donation:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the donation");
    }  
  }
  
  
  /**
   * Deletes an donation from the database
   * 
   * @param int $donationId The unique identifier of the donation to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteDonationyId(int $donationId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve donation.
      $query = $this->db->prepare("DELETE FROM donations WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $donationId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the donation: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the donation");
    }  
  }


  /**
     * Helper method to hydrate Donation object from data.
     * 
     * @param $donationData The data of the donation retrieve from the database.
     * 
     * @return Donation The retrieved donation
     */
    private function hydrateDonation($donationData): Donation
    {
      // Instantiate a new donation with retrieved data
      $donation = new Donation(
        $donationData["id"],
        $donationData["user_id"],
        $donationData["amount"],
        $donationData["donation_date"],
        $donationData["message"],
        $donationData["anonymous"],
        $donationData["last_name"],
        $donationData["first_name"]
    );
      return $donation;
    }
}