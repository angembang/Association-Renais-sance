<?php

/**
 * Manages the retrieval and persistence of membership object in the platform
 */
class MembershipManager extends AbstractManager
{
  /**
   * creates a new membership and persists it in the database.
   * 
   * @param Membership $membership The membership object to be created.
   * 
   * @return Membership The created membership object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createMembership(Membership $membership): Membership
  {
    try {
      // Prepare the SQL query to insert a new membership into the database
      $query = $this->db->prepare("INSERT INTO memberships ( civility, role_id, first_name, last_name, email, phone,  address, postal_code, created_at, logo, company_name, membership_fee) 
      VALUES (:civility, :role_id, :first_name, :last_name, :email, :phone, :address, :postal_code, :created_at, :logo, :company_name, :membership_fee)");

      // Bind the parameters with their values.
      $parameters = [
        ":last_name" => $membership->getLastName(),
        ":first_name" => $membership->getFirstName(),
        ":email" => $membership->getEmail(),
        ":phone" => $membership->getPhone(),
        ":address" => $membership->getAddress(),
        ":created_at" => $membership->getCreatedAt(),
        ":civility" => $membership->getCivility(),
        ":role_id" => $membership->getRoleId(),
        ":postal_code" => $membership->getPostalCode(),
        ":logo" => $membership->getLogo(),
        ":company_name" =>$membership->getCompanyName(),
        ":membership_fee" => $membership->getMembershipFee()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $membershipId = $this->db->lastInsertId();

      // Set the identifier for the created membership
      $membership->setId($membershipId);

      // Return the created membership object
      return $membership;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a membership" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a membership");
    }
  }
  


  /**
   * Retrieves a membership by his unique identifier
   * 
   * @param int $membershipId The unique identifier of the membership
   * 
   * @return Membership|null The retrieved membership or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findMembershipById(int $membershipId): ?Membership
  {
    try {
      // Prepare the SQL query to retrieve the membership by its unique identifier
      $query = $this->db->prepare("SELECT * FROM memberships WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $membershipId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the membership data from the database
      $membershipData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if membership is found
      if ($membershipData) {
        return $this->hydrateMembership($membershipData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a membership:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a membership");
    }     
  }


  /**
   * Retrieves a membership by its email 
   * 
   * @param string $email The email of the membership.
   * 
   * @return Membership|null The retrieved membership or null if not found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findMembershipByEmail(string $email): ?Membership
  {
    try {
      // Prepare the SQL query to retrieve the membership by its email.
      $query = $this->db->prepare("SELECT * FROM memberships WHERE email= :email");

      // Bind the parameter with its value.
      $parameter = [
        ":email" => $email
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the membership data from the database
      $membershipData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if membership is found
      if ($membershipData) {
        return $this->hydrateMembership($membershipData); 
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find a membership:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find a membership");
    }  
  }


  /**
   * Retrieves a membership by its phone
   * 
   * @param string $phone The phone of the membership.
   * 
   * @return Membership|null The retrieved membership or null if not found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findMembershipByPhone(string $phone): ?Membership
  {
    try {
      // Prepare the SQL query to retrieve the membership by its phone.
      $query = $this->db->prepare("SELECT * FROM memberships WHERE phone= :phone");

      // Bind the parameter with its value.
      $parameter = [
        ":phone" => $phone
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the membership data from the database
      $membershipData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if membership is found
      if ($membershipData) {
        return $this->hydrateMembership($membershipData); 
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find a membership:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find a membership");
    }  
  }


  /**
   * Retrieves memberships by their role identiier
   * 
   * @param int $roleId The role identiier of memberships.
   * 
   * @return array|null The array of membership retrieved by their role identiier or null if no membership is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findMembershipsByRoleId(int $roleId): ?array
  {
    try {
      // Prepare the SQL query to retrieve the memberships by their role.
      $query = $this->db->prepare("SELECT * FROM memberships WHERE role_id = :role_id");

      // Bind the parameter with its value.
      $parameter = [
        ":role_id" => $roleId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch memberships data from the database
      $membershipsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if memberships data is not empty
      if($membershipsData) {
        $memberships = [];
        // Loop through each membership data
        foreach($membershipsData as $membershipData) {
          // Instantiate a membership for each user data
          $membership = new Membership(
            $membershipData["id"],
            $membershipData["civility"],
            $membershipData["role_id"],
            $membershipData["first_name"],
            $membershipData["last_name"],
            $membershipData["email"],
            $membershipData["phone"],
            $membershipData["address"],
            $membershipData["postal_code"],
            $membershipData["created_at"],
            $membershipData["logo"],
            $membershipData["company_name"],
            $membershipData["membership_fee"]
          );
          // Add the instantiated membership object to the memberships array
          $memberships[] = $membership;
        }
        // Return the array of the membership objects
        return $memberships;
      }
      // No membership is found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find a membership:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find a membership");
    }   
  }


  /**
   * Retrieves all memberships
   *
   * @return array Membership|null The array of membership or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all memberships into the database
      $query = $this->db->prepare("SELECT * FROM memberships");

      // Execute the query
      $query->execute();

      // Fetch users data from the database
      $membershipsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if memberships data is not empty
      if($membershipsData) {
        $memberships = [];
        // Loop through each membership data
        foreach($membershipsData as $membershipData) {
          // Instantiate a membership for each user data
          $membership = new Membership(
            $membershipData["id"],
            $membershipData["civility"],
            $membershipData["role_id"],
            $membershipData["first_name"],
            $membershipData["last_name"],
            $membershipData["email"],
            $membershipData["phone"],
            $membershipData["address"],
            $membershipData["postal_code"],
            $membershipData["created_at"],
            $membershipData["logo"],
            $membershipData["company_name"],
            $membershipData["membership_fee"]
          );
          // Add the instantiated membership object to the memberships array
          $memberships[] = $membership;
        }
        // Return the array of the membership objects
        return $memberships;
      }
      // No membership is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find memberships:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find memberships");
    }  
  }


  /**
   * Updates a membership in the database
   * 
   * @param Membership $membership Themembership to be updated.
   * 
   * @return Membership|null The membership updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateMembership(Membership $membership): ?Membership
  {
    try {
      // Prepare the SQL query to update a membership.
      $query = $this->db->prepare("UPDATE memberships SET 
      last_name = :last_name,
      first_name = :first_name,
      email = :email,
      phone = :phone,
      address = :address,
      postal_code = :postal_code,
      created_at = :created_at,
      civility = :civility,
      role_id = :role_id,
      logo = :logo,
      company_name = :company_name,
      membership_fee = :membership_fee
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $membership->getId(),
        ":last_name" => $membership->getLastName(),
        ":first_name" => $membership->getFirstName(),
        ":email" => $membership->getEmail(),
        ":phone" => $membership->getPhone(),
        ":address" => $membership->getAddress(),
        ":postal_code" => $membership->getPostalCode(),
        ":created_at" => $membership->getCreatedAt(),
        ":civility" => $membership->getCivility(),
        ":role_id" => $membership->getRoleId(),
        ":logo" => $membership->getLogo(),
        ":company_name" => $membership->getCompanyName(),
        ":membership_fee" => $membership->getMembershipFee()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $membership;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the membership:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the membership");
    }  
  }
  
  
  /**
   * Deletes a membership from the database
   * 
   * @param int $membershipId The unique identifier of the membership to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteMembershipById(int $membershipId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve membership.
      $query = $this->db->prepare("DELETE FROM memberships WHERE id = :id");

      // Bind the parameter its their value
      $parameters = [
        ":id" => $membershipId
      ];

      // Execute the query with the parameter
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the membership: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the membership");
    }  
  }


  /**
     * Helper method to hydrate Membership object from data.
     * 
     * @param $membershipData The data of the membership retrieve from the database.
     * 
     * @return Membership The retrieved membership.
     */
    private function hydrateMembership($membershipData): Membership
    {
      // Instantiate a new membership with retrieved data
      $membership = new Membership(
        $membershipData["id"],
        $membershipData["civility"],
        $membershipData["role_id"],
        $membershipData["first_name"],
        $membershipData["last_name"],
        $membershipData["email"],
        $membershipData["phone"],
        $membershipData["address"],
        $membershipData["postal_code"],
        $membershipData["created_at"],
        $membershipData["logo"],
        $membershipData["company_name"],
        $membershipData["membership_fee"]
      );
      return $membership;
    }
}