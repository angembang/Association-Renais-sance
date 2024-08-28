<?php

/**
 * Manages the retrieval and persistence of Role object in the platform
 */
class RoleManager extends AbstractManager
{
  /**
   * creates a new role and persists it in the database.
   * 
   * @param Role $role The role object to be created.
   * 
   * @return Role The created role object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createRole(Role $role): Role
  {
    try {
      // Prepare the SQL query to insert a new role into the database
      $query = $this->db->prepare("INSERT INTO roles (name, description) 
      VALUES (:name, :description)");

      // Bind the parameters with their values.
      $parameters = [
        ":name" => $role->getName(),
        ":description" => $role->getDescription()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $roleId = $this->db->lastInsertId();

      // Set the identifier for the created role
      $role->setId($roleId);

      // Return the created role object
      return $role;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a role" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a role");
    }
  }
  


  /**
   * Retrieves an role by its unique identifier
   * 
   * @param int $roleId The unique identifier of the role
   * 
   * @return Role|null The retrieved role or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findRoleById(int $roleId): ?Role
  {
    try {
      // Prepare the SQL query to retrieve the role by its unique identifier
      $query = $this->db->prepare("SELECT * FROM roles WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $roleId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the user data from the database
      $roleData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if user is found
      if ($roleData) {
        return $this->hydrateRole($roleData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a role:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a role");
    }     
  }


  /**
   * Retrieves an role by its name
   * 
   * @param string $name The name of the role.
   * 
   * @return Role|null The retrieved role or null if not found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findRoleByName(string $name): ?Role
  {
    try {
      // Prepare the SQL query to retrieve the role by its name.
      $query = $this->db->prepare("SELECT * FROM roles WHERE name= :name");

      // Bind the parameter with its value.
      $parameter = [
        ":name" => $name
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the role data from the database
      $roleData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if role is found
      if ($roleData) {
        return $this->hydrateRole($roleData); 
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find a role" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find a role");
    }  
  }


  /**
   * Retrieves all roles
   *
   * @return array|null The array of role or null if no role is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all roles into the database
      $query = $this->db->prepare("SELECT * FROM roles");

      // Execute the query
      $query->execute();

      // Fetch roles data from the database
      $rolesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if roles data is not empty
      if($rolesData) {
        $roles = [];
        // Loop through each roledata
        foreach($rolesData as $roleData) {
          // Instantiate an role for each role data
          $role = new Role(
            $roleData["id"],
            $roleData["name"],
            $roleData["description"]
          );
          // Add the instantiated role object to the roles array
          $roles[] = $role;
        }
        // Return the array of the role objects
        return $roles;
      }
      // No role is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find roles:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find roles");
    }  
  }


  /**
   * Updates an role in the database
   * 
   * @param Role $role The role to be updated.
   * 
   * @return Role|null The role updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updatRole(Role $role): ?Role
  {
    try {
      // Prepare the SQL query to update an role.
      $query = $this->db->prepare("UPDATE roles SET 
      name = :name,
      description = :description
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $role->getId(),
        ":name" => $role->getName(),
        ":description" => $role->getDescription()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $role;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the role:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the role");
    }  
  }
  
  
  /**
   * Deletes an role from the database
   * 
   * @param int $roleId The unique identifier of the role to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteRoleById(int $roleId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve role.
      $query = $this->db->prepare("DELETE FROM roles WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $roleId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the role: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the role");
    }  
  }


  /**
     * Helper method to hydrate Role object from data.
     * 
     * @param $roleData The data of the role retrieve from the database.
     * 
     * @return Role The retrieved role.
     */
    private function hydrateRole($roleData): Role
    {
      // Instantiate a new role with retrieved data
      $role = new Role(
        $roleData["id"],
        $roleData["name"],
        $roleData["description"]
      );
      return $role;
    }
}