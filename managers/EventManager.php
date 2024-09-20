<?php
use PHPUnit\Framework\Constraint\StringStartsWith;

/**
 * Manages the retrieval and persistence of Event object in the platform
 */
class EventManager extends AbstractManager
{
  /**
   * creates a new event and persists it in the database.
   * 
   * @param Event $event The event object to be created.
   * 
   * @return Event The created event object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createEvent(Event $event): Event
  {
    try {
      // Prepare the SQL query to insert a new event into the database
      $query = $this->db->prepare("INSERT INTO events (title, description, start_date, end_date, location, organizer, seats_available, image, video) 
      VALUES (:title, :description, :start_date, :end_date, :location, :organizer, :seats_available, :image, :video)");

      // Format the DateTime objects to strings
      $startDateFormatted = $event->getStartDate()->format('Y-m-d H:i:s');
      $endDateFormatted = $event->getEndDate()->format('Y-m-d H:i:s');

      // Bind the parameters with their values.
      $parameters = [
        ":title" => $event->getTitle(),
        ":description" =>$event->getDescription(),
        ":start_date" => $startDateFormatted,
        ":end_date" => $endDateFormatted,
        ":location" => $event->getLocation(),
        ":organizer" => $event->getOrganiser(),
        ":seats_available" =>$event->getSeatsAvailable(),
        ":image" => $event->getImage(),
        ":video" => $event->getVideo()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $eventId = $this->db->lastInsertId();

      // Set the identifier for the created event
      $event->setId($eventId);

      // Return the created event object
      return $event;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create an event" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create an event");
    }
  }
  


  /**
   * Retrieves an event by its unique identifier
   * 
   * @param int $eventId The unique identifier of the event
   * 
   * @return Event|null The retrieved event. Null if not found
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findEventById(int $eventId): ?Event
  {
    try {
      // Prepare the SQL query to retrieve the event by its unique identifier
      $query = $this->db->prepare("SELECT * FROM events WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $eventId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the event data from the database
      $eventData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if event is found
      if ($eventData) {
        return $this->hydrateEvent($eventData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find an event:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find an event");
    }     
  }


  /**
   * Retrieves an event by its end date
   * 
   * @param DateTime $endDate The end date of the event
   * 
   * @return Event|null The retrieved event. null if not found
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findEventByEndDate(DateTime $endDate): ?Event
  {
    try {
      // Prepare the SQL query to retrieve the event by its end date
      $query = $this->db->prepare("SELECT * FROM event WHERE end_date = :end_date");
    
      // Bind the parameter with its value.
      $parameter = [
        ":end_date" => $endDate
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the event data from the database
      $eventData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if event is found
      if ($eventData) {
        return $this->hydrateEvent($eventData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find an event:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find an event");
    }     
  }


  /**
   * Retrieves the latest event insert
   * 
   * @return array|null The retrieved event or null if no news is found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findLatest(): ?Event
  {
    try {
      // Prepare the SQL query to retrieve the latest news (by publication date)
      $query = $this->db->prepare("SELECT * FROM events ORDER BY start_date DESC LIMIT 1");
      // Execute the query with the parameter
      $query->execute();

      // Fetch the event data from the database
      $eventData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if event is found
      if ($eventData) {
        return $this->hydrateEvent($eventData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find an event:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find an event");
    }     
  }


  /**
   * Retrieves all events
   *
   * @return array|null The array of event or null if no event is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all event into the database
      $query = $this->db->prepare("SELECT * FROM events");

      // Execute the query
      $query->execute();

      // Fetch event data from the database
      $eventsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if events data is not empty
      if($eventsData) {
        $events = [];
        // Loop through each eventsdata
        foreach($eventsData as $eventData) {
          // Instantiate an eventRegistratio for each event data
          $event = new Event(
            $eventData["id"],
            $eventData["title"],
            $eventData["description"],
            $eventData["start_date"],
            $eventData["end_date"],
            $eventData["location"],
            $eventData["organizer"],
            $eventData["seats_available"],
            $eventData["image"],
            $eventData["video"]
          );
          // Add the instantiated event object to the event array
          $events[] = $event;
        }
        // Return the array of the event objects
        return $events;
      }
      // No event is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find event:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find event");
    }  
  }


  /**
   * Updates an event in the database
   * 
   * @param Event $event The event to be updated.
   * 
   * @return Event|null The event updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateEvent(Event $event): ?Event
  {
    try {
      // Prepare the SQL query to update an event.
      $query = $this->db->prepare("UPDATE events SET 
      title = :title,
      description = :description,
      start_date = :start_date,
      end_date = :end_date,
      location = :location,
      organizer = :organizer,
      seats_available = :seats_available,
      image = :image,
      video = :video
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $event->getId(),
        ":title" => $event->getTitle(),
        ":description" => $event->getDescription(),
        ":start_date" => $event->getStartDate(),
        ":end_date" => $event->getEndDate(),
        ":location" => $event->getLocation(),
        ":organizer" => $event->getOrganiser(),
        ":seats_available" => $event->getSeatsAvailable(),
        ":image" => $event->getImage(),
        ":video" => $event->getVideo(),
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $event;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the event:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the event");
    }  
  }
  
  
  /**
   * Deletes an event from the database
   * 
   * @param int $eventId The unique identifier of the event to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteEventById(int $eventId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve event.
      $query = $this->db->prepare("DELETE FROM events WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $eventId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the event: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the event");
    }  
  }


  /**
     * Helper method to hydrate Event object from data.
     * 
     * @param $eventData The data of the event retrieve from the database.
     * 
     * @return Event The retrieved event
     */
    private function hydrateEvent($eventData): Event
    {
      $startDate = new DateTime($eventData["start_date"]);
      $endDate = new DateTime($eventData["end_date"]);
      // Instantiate a new event with retrieved data
      $event = new Event(
        $eventData["id"],
        $eventData["title"],
        $eventData["description"],
        $startDate,
        $endDate,
        $eventData["location"],
        $eventData["organizer"],
        $eventData["seats_available"],
        $eventData["image"],
        $eventData["video"]
      );
      return $event;
    }
}