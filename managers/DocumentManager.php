<?php
use PHPUnit\Framework\Constraint\StringStartsWith;

/**
 * Manages the retrieval and persistence of Document object in the platform
 */
class Documentanager extends AbstractManager
{
  /**
   * creates a new document and persists it in the database.
   * 
   * @param Document $document The document object to be created.
   * 
   * @return Document The created document object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createDocument(Document $document): Document
  {
    try {
      // Prepare the SQL query to insert a new document into the database
      $query = $this->db->prepare("INSERT INTO documents (name, type, file_path, date_added) 
      VALUES (:name, :type, :file_path, :date_added");

      // Bind the parameters with their values.
      $parameters = [
        ":name" => $document->getName(),
        ":type" => $document->getType(),
        ":file_path" => $document->getFilePath(),
        ":date_added" => $document->getDateAdded()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $documentId = $this->db->lastInsertId();

      // Set the identifier for the created document
      $document->setId($documentId);

      // Return the created document object
      return $document;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a document" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a document");
    }
  }
  


  /**
   * Retrieves an document by its unique identifier
   * 
   * @param int $documentId The unique identifier of the document
   * 
   * @return Document|null The retrieved document. Null if not found
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findDocumentById(int $documentId): ?Document
  {
    try {
      // Prepare the SQL query to retrieve the document by its unique identifier
      $query = $this->db->prepare("SELECT * FROM documents WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $documentId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the document data from the database
      $documentData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if document is found
      if ($documentData) {
        return $this->hydrateDocument($documentData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a document:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a document");
    }     
  }


  /**
   * Retrieves all documents
   *
   * @return array|null The array of document or null if no document is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all document into the database
      $query = $this->db->prepare("SELECT * FROM documents");

      // Execute the query
      $query->execute();

      // Fetch document data from the database
      $documentsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if documents data is not empty
      if($documentsData) {
        $documents = [];
        // Loop through each documents data
        foreach($documentsData as $documentData) {
          // Instantiate an document for each document data
          $document = new Document(
            $documentData["id"],
            $documentData["name"],
            $documentData["type"],
            $documentData["file_path"],
            $documentData["date_added"]
          );
          // Add the instantiated document object to the document array
          $documents[] = $document;
        }
        // Return the array of the document objects
        return $documents;
      }
      // No document is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find document:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find document");
    }  
  }


  /**
   * Updates an documentin the database
   * 
   * @param Document $document The document to be updated.
   * 
   * @return Document|null The document updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateDocument(Document $document): ?Document
  {
    try {
      // Prepare the SQL query to update an document
      $query = $this->db->prepare("UPDATE documents SET 
      name = :name,
      type = :type,
      file_path= :file_path,
      date_added = :date_added
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $document->getId(),
        ":name" => $document->getName(),
        ":type" => $document->getType(),
        ":file_path" => $document->getFilePath(),
        ":date_added" => $document->getDateAdded()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $document;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the document" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the document");
    }  
  }
  
  
  /**
   * Deletes an document from the database
   * 
   * @param int $documentId The unique identifier of the documentto be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteDocumentId(int $documentId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve document.
      $query = $this->db->prepare("DELETE FROM documents WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $documentId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the document: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the document");
    }  
  }


  /**
     * Helper method to hydrate Document object from data.
     * 
     * @param $documentData The data of the document retrieve from the database.
     * 
     * @return Document The retrieved document
     */
    private function hydrateDocument($documentData): Document
    {
      // Instantiate a new document with retrieved data
      $document = new Document(
        $documentData["id"],
            $documentData["name"],
            $documentData["type"],
            $documentData["file_path"],
            $documentData["date_added"]
    );
      return $document;
    }
}