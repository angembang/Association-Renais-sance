<?php
use PHPUnit\Framework\Constraint\StringStartsWith;

/**
 * Manages the retrieval and persistence of Message object in the platform
 */
class MessageManager extends AbstractManager
{
  /**
   * creates a new message and persists it in the database.
   * 
   * @param Message $message The message object to be created.
   * 
   * @return Message The created message object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createMessage(Message $message): Message
  {
    try {
      // Prepare the SQL query to insert a new message into the database
      $query = $this->db->prepare("INSERT INTO messages (sender_id, recipients, subject, body, send_date) 
      VALUES (:sender_id, :recipients, :subject, :body, :send_date)");

      // Bind the parameters with their values.
      $parameters = [
        ":sender_id" => $message->getSenderId(),
        ":recipients" =>$message->getRecipients(),
        ":subject" => $message->getSubject(),
        ":body" => $message->getBody(),
        ":send_date" => $message->getSendDate()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $messageId = $this->db->lastInsertId();

      // Set the identifier for the created message
      $message->setId($messageId);

      // Return the created message object
      return $message;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a message" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a message");
    }
  }
  


  /**
   * Retrieves an message by its unique identifier
   * 
   * @param int $messageId The unique identifier of the message
   * 
   * @return Message|null The retrieved message or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findMessageById(int $messageId): ?Message
  {
    try {
      // Prepare the SQL query to retrieve the message by its unique identifier
      $query = $this->db->prepare("SELECT * FROM messages WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $messageId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the message data from the database
      $messageData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if message is found
      if ($messageData) {
        return $this->hydrateMessage($messageData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a message:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a message");
    }     
  }


  /**
   * Retrieves messages by its recipients
   * 
   * @param string $recipients The recipients of the message.
   * 
   * @return array|null The retrieved messages or null if no message is found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findMessagesByRecipients(String $recipients): ?array
  {
    try {
      // Prepare the SQL query to retrieve messages by their recipients.
      $query = $this->db->prepare("SELECT * FROM messages WHERE recipients= :recipients");

      // Bind the parameter with its value.
      $parameter = [
        ":recipients" => $recipients
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch message data from the database
      $messagesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if messages data is not empty
      if($messagesData) {
        $messages = [];
        // Loop through each messagesdata
        foreach($messagesData as $messageData) {
          // Instantiate an message for each message data
          $message = new Message(
            $messageData["id"],
            $messageData["sender_id"],
            $messageData["recipients"],
            $messageData["subject"],
            $messageData["body"],
            $messageData["send_date"]
          );
          // Add the instantiated message object to the message array
          $messages[] = $message;
        }
        // Return the array of the message objects
        return $messages;
      }
      // No message is found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find a message" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find a message");
    }  
  }


  /**
   * Retrieves all messages
   *
   * @return array|null The array of message or null if no message is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all news into the database
      $query = $this->db->prepare("SELECT * FROM messages");

      // Execute the query
      $query->execute();

      // Fetch message data from the database
      $messagesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if messages data is not empty
      if($messagesData) {
        $messages = [];
        // Loop through each messagesdata
        foreach($messagesData as $messageData) {
          // Instantiate an message for each message data
          $message = new Message(
            $messageData["id"],
            $messageData["sender_id"],
            $messageData["recipients"],
            $messageData["subject"],
            $messageData["body"],
            $messageData["send_date"]
          );
          // Add the instantiated message object to the message array
          $messages[] = $message;
        }
        // Return the array of the message objects
        return $messages;
      }
      // No message is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find news:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find news");
    }  
  }


  /**
   * Updates an message in the database
   * 
   * @param Message $message The message to be updated.
   * 
   * @return Message|null The message updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateMessage(Message $message): ?Message
  {
    try {
      // Prepare the SQL query to update an message.
      $query = $this->db->prepare("UPDATE messages SET 
      sender_id = :sender_id,
      recipients = :recipients,
      subject = :subject,
      body = :body,
     send_date = :send_date
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $message->getId(),
        ":sender_id" => $message->getSenderId(),
        ":recipients" => $message->getRecipients(),
        ":subject" => $message->getSubject(),
        ":body" => $message->getBody(),
        ":send_date" => $message->getSendDate()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $message;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the message:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the message");
    }  
  }
  
  
  /**
   * Deletes an message from the database
   * 
   * @param int $messageId The unique identifier of the message to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteMessageById(int $messageId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve message.
      $query = $this->db->prepare("DELETE FROM messages WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $messageId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the message: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the message");
    }  
  }


  /**
     * Helper method to hydrate Message object from data.
     * 
     * @param $messageData The data of the message retrieve from the database.
     * 
     * @return Message The retrieved message
     */
    private function hydrateMessage($messageData): Message
    {
      // Instantiate a new message with retrieved data
      $message = new Message(
        $messageData["id"],
        $messageData["sender_id"],
        $messageData["recipients"],
        $messageData["subject"],
        $messageData["body"],
        $messageData["send_date"]
      );
      return $message;
    }
}