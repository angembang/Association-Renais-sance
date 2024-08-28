<?php
use PHPUnit\Framework\Constraint\StringStartsWith;

/**
 * Manages the retrieval and persistence of EventRegistration object in the platform
 */
class EventRegistrationManager extends AbstractManager
{
  /**
   * creates a new event registration and persists it in the database.
   * 
   * @param EventRegistration $eventRegistration The event registration object to be created.
   * 
   * @return EventRegistration The created event registration object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createEventRegistration(EventRegistration $eventRegistration): EventRegistration
  {
    try {
      // Prepare the SQL query to insert a new event registration into the database
      $query = $this->db->prepare("INSERT INTO eventRegistrations (event_id, user_id, registration_date, last_name, first_name) 
      VALUES (:event_id, :user_id, :registration_date, :last_name, :first_name)");

      // Bind the parameters with their values.
      $parameters = [
        ":event_id" => $eventRegistration->geteventId(),
        ":user_id" =>$eventRegistration->getUserId(),
        ":registration_date" => $eventRegistration->getRegistrationDate(),
        ":last_name" => $eventRegistration->getLastName(),
        ":first_name" => $eventRegistration->getFirstName()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $eventRegistrationId = $this->db->lastInsertId();

      // Set the identifier for the created eventRegistration
      $eventRegistration->setId($eventRegistrationId);

      // Return the created eventRegistration object
      return $eventRegistration;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create an eventRegistration" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create an eventRegistration");
    }
  }
  


  /**
   * Retrieves an eventRegistration by its unique identifier
   * 
   * @param int $eventRegistrationId The unique identifier of the eventRegistration
   * 
   * @return EventRegistration|null The retrieved eventRegistration. Null if not found
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findEventRegistrationById(int $eventRegistrationId): ?EventRegistration
  {
    try {
      // Prepare the SQL query to retrieve the eventRegistration by its unique identifier
      $query = $this->db->prepare("SELECT * FROM eventRegistrations WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $eventRegistrationId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the event register data from the database
      $eventRegistrationData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if eventRegistration is found
      if ($eventRegistrationData) {
        return $this->hydrateEventRegistration($eventRegistrationData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find an eventRegistration:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find an eventRegistration");
    }     
  }


  /**
   * Retrieves eventRegistrations by its event identifier
   * 
   * @param int $event_id The event identifier of the eventRegistration.
   * 
   * @return array|null The retrieved eventRegistrations or null if no eventRegistration is found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findEventRegistrationsByEventId(int $eventId): ?array
  {
    try {
      // Prepare the SQL query to retrieve eventRegistration by their event identifier.
      $query = $this->db->prepare("SELECT * FROM eventRegistrations WHERE event_id= :event_id");

      // Bind the parameter with its value.
      $parameter = [
        ":event_id" => $eventId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch eventRegistration data from the database
      $eventRegistrationsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if eventRegistrations data is not empty
      if($eventRegistrationsData) {
        $eventRegistrations = [];
        // Loop through each eventRegistrationsdata
        foreach($eventRegistrationsData as $eventRegistrationData) {
          // Instantiate an eventRegistration for each eventRegistration data
          $eventRegistration = new EventRegistration(
            $eventRegistrationData["id"],
            $eventRegistrationData["event_id"],
            $eventRegistrationData["user_id"],
            $eventRegistrationData["registration_date"],
            $eventRegistrationData["last_name"],
            $eventRegistrationData["first_name"]
          );
          // Add the instantiated eventRegistration object to the eventRegistration array
          $eventRegistrations[] = $eventRegistration;
        }
        // Return the array of the eventRegistration objects
        return $eventRegistrations;
      }
      // No eventRegistration is found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find an eventRegistration" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find an eventRegistration");
    }  
  }


  /**
   * Retrieves all eventRegistrations
   *
   * @return array|null The array of eventRegistration or null if no eventRegistration is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all eventRegistration into the database
      $query = $this->db->prepare("SELECT * FROM eventRegistrations");

      // Execute the query
      $query->execute();

      // Fetch eventRegistration data from the database
      $eventRegistrationsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if eventRegistrations data is not empty
      if($eventRegistrationsData) {
        $eventRegistrations = [];
        // Loop through each eventRegistrationsdata
        foreach($eventRegistrationsData as $eventRegistrationData) {
          // Instantiate an eventRegistration for each eventRegistration data
          $eventRegistration = new EventRegistration(
            $eventRegistrationData["id"],
            $eventRegistrationData["event_id"],
            $eventRegistrationData["user_id"],
            $eventRegistrationData["registration_date"],
            $eventRegistrationData["last_name"],
            $eventRegistrationData["first_name"]
          );
          // Add the instantiated eventRegistration object to the eventRegistration array
          $eventRegistrations[] = $eventRegistration;
        }
        // Return the array of the eventRegistration objects
        return $eventRegistrations;
      }
      // No eventRegistration is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find event registration" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find event registration");
    }  
  }


  /**
   * Updates an event registretion in the database
   * 
   * @param EventRegistration $eventRegistration The eventRegistration to be updated.
   * 
   * @return EventRegistration|null The eventRegistration updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateEventRegistration(EventRegistration $eventRegistration): ?EventRegistration
  {
    try {
      // Prepare the SQL query to update an eventRegistration.
      $query = $this->db->prepare("UPDATE eventRegistrations SET 
      event_id = :event_id,
      user_id = :user_id,
      registration_date = :registration_date,
      last_name = :last_name,
      first_name = :first_name
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $eventRegistration->getId(),
        ":event_id" => $eventRegistration->getEventId(),
        ":user_id" => $eventRegistration->getUserId(),
        ":registration_date" => $eventRegistration->getRegistrationDate(),
        ":last_name" => $eventRegistration->getLastName(),
        ":first_name" => $eventRegistration->getFirstName()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $eventRegistration;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the eventRegistration:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the eventRegistration");
    }  
  }
  
  
  /**
   * Deletes an eventRegistration from the database
   * 
   * @param int $eventRegistrationId The unique identifier of the eventRegistration to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteEventRegistrationById(int $eventRegistrationId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve eventRegistration.
      $query = $this->db->prepare("DELETE FROM eventRegistrations WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $eventRegistrationId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the eventRegistration: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the eventRegistration");
    }  
  }


  /**
     * Helper method to hydrate EventRegistration object from data.
     * 
     * @param $eventRegistrationData The data of the eventRegistration retrieve from the database.
     * 
     * @return EventRegistration The retrieved eventRegistration
     */
    private function hydrateEventRegistration($eventRegistrationData): EventRegistration
    {
      // Instantiate a new eventRegistration with retrieved data
      $eventRegistration = new EventRegistration(
        $eventRegistrationData["id"],
        $eventRegistrationData["event_id"],
        $eventRegistrationData["user_id"],
        $eventRegistrationData["rgistration_date"],
        $eventRegistrationData["last_name"],
        $eventRegistrationData["first_name"]
      );
      return $eventRegistration;
    }
}