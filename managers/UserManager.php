<?php

/**
 * Manages the retrieval and persistence of User object in the platform
 */
class UserManager extends AbstractManager
{
  /**
   * creates a new user and persists it in the database.
   * 
   * @param User $user The user object to be created.
   * 
   * @return User The created user object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createUser(User $user): User
  {
    try {
      // Prepare the SQL query to insert a new user into the database
      $query = $this->db->prepare("INSERT INTO users ( last_name, first_name, email, role_id, password) 
      VALUES (:last_name, :first_name, :email, :role_id, :password)");

      // Bind the parameters with their values.
      $parameters = [
        ":last_name" => $user->getLastName(),
        ":first_name" => $user->getFirstName(),
        ":email" => $user->getEmail(),
        ":role_id" => $user->getRoleId(),
        ":password" => $user->getPassword()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $userId = $this->db->lastInsertId();

      // Set the identifier for the created user
      $user->setId($userId);

      // Return the created user object
      return $user;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create an user" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create an user");
    }
  }
  


  /**
   * Retrieves an user by his unique identifier
   * 
   * @param int $userId The unique identifier of the user
   * 
   * @return User|null The retrieved user or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findUserById(int $userId): ?User
  {
    try {
      // Prepare the SQL query to retrieve the user by its unique identifier
      $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $userId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the user data from the database
      $userData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if user is found
      if ($userData) {
        return $this->hydrateUser($userData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find an user:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find an user");
    }     
  }


  /**
   * Retrieves an user by its email 
   * 
   * @param string $email The email of the user.
   * 
   * @return User|null The retrieved user or null if not found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findUserByEmail(string $email): ?User
  {
    try {
      // Prepare the SQL query to retrieve the user by its email.
      $query = $this->db->prepare("SELECT * FROM users WHERE email= :email");

      // Bind the parameter with its value.
      $parameter = [
        ":email" => $email
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the user data from the database
      $userData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if user is found
      if ($userData) {
        return $this->hydrateUser($userData); 
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find an user:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find an user");
    }  
  }


  /**
   * Retrieves users by their role identiier
   * 
   * @param int $roleId The role identiier of users.
   * 
   * @return array|null The array of user retrieved by their role identiier or null if no is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findUsersByRoleId(int $roleId): ?array
  {
    try {
      // Prepare the SQL query to retrieve the users by their role.
      $query = $this->db->prepare("SELECT * FROM users WHERE role_id = :role_id");

      // Bind the parameter with its value.
      $parameter = [
        ":role_id" => $roleId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch users data from the database
      $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if users data is not empty
      if($usersData) {
        $users = [];
        // Loop through each user data
        foreach($usersData as $userData) {
          // Instantiate an user for each user data
          $user = new User(
            $userData["id"],
            $userData["first_name"],
            $userData["last_name"],
            $userData["email"],
            $userData["role_id"],
            $userData["password"]
          );
          // Add the instantiated user object to the users array
          $users[] = $user;
        }
        // Return the array of the user objects
        return $users;
      }
      // No user is found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find an user:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find an user");
    }   
  }


  /**
   * Retrieves all users
   *
   * @return array User|null The array of user or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all users into the database
      $query = $this->db->prepare("SELECT * FROM users");

      // Execute the query
      $query->execute();

      // Fetch users data from the database
      $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if users data is not empty
      if($usersData) {
        $users = [];
        // Loop through each user data
        foreach($usersData as $userData) {
          // Instantiate an user for each user data
          $user = new User(
            $userData["id"],
            $userData["first_name"],
            $userData["last_name"],
            $userData["email"],
            $userData["role_id"],
            $userData["password"]
          );
          // Add the instantiated user object to the users array
          $users[] = $user;
        }
        // Return the array of the user objects
        return $users;
      }
      // No user is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find users:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find users");
    }  
  }


  /**
   * Updates an user in the database
   * 
   * @param User $user The user to be updated.
   * 
   * @return User|null The user updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateUser(User $user): ?User
  {
    try {
      // Prepare the SQL query to update an user.
      $query = $this->db->prepare("UPDATE users SET 
      last_name = :last_name,
      first_name = :first_name,
      email = :email,
      role_id = :role_id,
      password = :password
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $user->getId(),
        ":last_name" => $user->getLastName(),
        ":first_name" => $user->getFirstName(),
        ":email" => $user->getEmail(),
        ":role_id" => $user->getRoleId(),
        ":password" => $user->getPassword()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $user;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the user:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the user");
    }  
  }
  
  
  /**
   * Deletes an user from the database
   * 
   * @param int $userId The unique identifier of the user to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteUserById(int $userId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve user.
      $query = $this->db->prepare("DELETE FROM users WHERE id = :id");

      // Bind the parameter its their value
      $parameters = [
        ":id" => $userId
      ];

      // Execute the query with the parameter
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the user: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the user");
    }  
  }


  /**
     * Helper method to hydrate User object from data.
     * 
     * @param $userData The data of the user retrieve from the database.
     * 
     * @return User The retrieved user.
     */
    private function hydrateUser($userData): User
    {
      // Instantiate a new user with retrieved data
      $user = new User(
        $userData["id"],
        $userData["first_name"],
        $userData["last_name"],
        $userData["email"],
        $userData["role_id"],
        $userData["password"]
      );
      return $user;
    }
}