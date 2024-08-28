<?php

/**
 * Manages the retrieval and persistence of Resource object in the platform
 */
class ResourceManager extends AbstractManager
{
  /**
   * creates a new resource and persists it in the database.
   * 
   * @param Resource $resource The resource object to be created.
   * 
   * @return Resource The created resource object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createResource(Resource $resource): Resource
  {
    try {
      // Prepare the SQL query to insert a new resource into the database
      $query = $this->db->prepare("INSERT INTO resources (name, type, description, quantity) 
      VALUES (:name, :description)");

      // Bind the parameters with their values.
      $parameters = [
        ":name" => $resource->getName(),
        ":type" =>$resource->getType(),
        ":description" => $resource->getDescription(),
        "quantity" => $resource->getQuantity()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $resourceId = $this->db->lastInsertId();

      // Set the identifier for the created resource
      $resource->setId($resourceId);

      // Return the created resource object
      return $resource;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a resource" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a resource");
    }
  }
  


  /**
   * Retrieves an resource by its unique identifier
   * 
   * @param int $resourceId The unique identifier of the resource
   * 
   * @return Resource|null The retrieved resource or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findResourceById(int $resourceId): ?Resource
  {
    try {
      // Prepare the SQL query to retrieve the resource by its unique identifier
      $query = $this->db->prepare("SELECT * FROM resources WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $resourceId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the user data from the database
      $resourceData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if user is found
      if ($resourceData) {
        return $this->hydrateResource($resourceData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a resource:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a resource");
    }     
  }


  /**
   * Retrieves an resource by its name
   * 
   * @param string $name The name of the resource.
   * 
   * @return Resource|null The retrieved resource or null if not found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findResourceByName(string $name): ?Resource
  {
    try {
      // Prepare the SQL query to retrieve the resource by its name.
      $query = $this->db->prepare("SELECT * FROM resources WHERE name= :name");

      // Bind the parameter with its value.
      $parameter = [
        ":name" => $name
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the resource data from the database
      $resourceData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if resource is found
      if ($resourceData) {
        return $this->hydrateResource($resourceData); 
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find a resource" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find a resource");
    }  
  }


  /**
   * Retrieves all resources
   *
   * @return array|null The array of resource or null if no resource is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all resources into the database
      $query = $this->db->prepare("SELECT * FROM resources");

      // Execute the query
      $query->execute();

      // Fetch resources data from the database
      $resourcesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if resources data is not empty
      if($resourcesData) {
        $resources = [];
        // Loop through each resourcedata
        foreach($resourcesData as $resourceData) {
          // Instantiate an resource for each resource data
          $resource = new Resource(
            $resourceData["id"],
            $resourceData["name"],
            $resourceData["type"],
            $resourceData["description"],
            $resourceData["quantity"]
          );
          // Add the instantiated resource object to the resources array
          $resources[] = $resource;
        }
        // Return the array of the resourcee objects
        return $resources;
      }
      // No resource is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find resources:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find resources");
    }  
  }


  /**
   * Updates an resource in the database
   * 
   * @param Resource $resource The resource to be updated.
   * 
   * @return Resource|null The resource updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updatResource(Resource $resource): ?Resource
  {
    try {
      // Prepare the SQL query to update an resource.
      $query = $this->db->prepare("UPDATE resource SET 
      name = :name,
      type = :type,
      description = :description,
      quantity = :quantity
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $resource->getId(),
        ":name" => $resource->getName(),
        ":type" => $resource->getType(),
        ":description" => $resource->getDescription(),
        ":quantity" =>$resource->getQuantity()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $resource;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the resource:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the resource");
    }  
  }
  
  
  /**
   * Deletes an resource from the database
   * 
   * @param int $resourceId The unique identifier of the resource to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteResourceById(int $resourceId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve resource.
      $query = $this->db->prepare("DELETE FROM resources WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $resourceId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the resource: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the resource");
    }  
  }


  /**
     * Helper method to hydrate Resource object from data.
     * 
     * @param $resourceData The data of the resource retrieve from the database.
     * 
     * @return Resource The retrieved resource
     */
    private function hydrateResource($resourceData): Resource
    {
      // Instantiate a new resource with retrieved data
      $resource = new Resource(
        $resourceData["id"],
        $resourceData["name"],
        $resourceData["type"],
        $resourceData["description"],
        $resourceData["quantity"]
      );
      return $resource;
    }
}