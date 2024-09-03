<?php

/** 
 * Class User
 * Defines an user in the plateform.
 */
class User 
{
  /**
   * @var int|null the unique identifier of the user. Null for a new user (not yet store in the database) 
   */ 
  private ?int $id;

  /**
   * @var string the first name of the user. 
   */ 
  private string $firstName;

   /**
   * @var string the last name of the user. 
   */ 
  private string $lastName;
  
  /**
   * @var string the email of the user. 
   */ 
  private string $email;
  
  /**
   * @var string the password of the user. 
   */
  private string $password;
  
  /**
   * @var int the role identifier of the user. 
   */
   private int $roleId;
  

  /**
   * User constructor
   * @param int|null $id The unique identifier of the user. Null for a new user.
   * @param string $firstName The first name of the user.
   * @param string $lastName The last name of the user.
   * @param string $email The email of the user.
   * @param string $password The password of the user.
   * @param int $roleId The role identifier of the user.
   */
  public function __construct(?int $id, string $lastName, string $firstName, string $email, int $roleId, string $password)
  {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->email = $email;
    $this->password = $password;
    $this->roleId = $roleId;
  }

  
  /**
   * Get the unique identifier of the user
   * 
   * @return int|null The unique identifier of the user. Null for a new user.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the user.
   * @param int|null $id The unique identifier of the user. 
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the first name of the user
   * 
   * @return string The first name of the user. 
   */
  public function getFirstName(): string 
  {
    return $this->firstName;
  }

  /**
   * Set the first name of the user.
   * @param string $firstName The first name of the user. 
   */
  public function setFirstName(string $firstName): void 
  {
    $this->firstName = $firstName;
  }


  /**
   * Get the last name of the user
   * 
   * @return string The last name of the user. 
   */
  public function getLastName(): string 
  {
    return $this->lastName;
  }

  /**
   * Set the last name of the user.
   * @param string $lastName The last name of the user. 
   */
  public function setLastName(string $lastName): void 
  {
    $this->lastName = $lastName;
  }



  /**
   * Get the email of the user
   * 
   * @return string The email of the user. 
   */
  public function getEmail(): string 
  {
    return $this->email;
  }

  /**
   * Set the email of the user.
   * @param string $email The email of the user. 
   */
  public function setEmail(string $email): void 
  {
    $this->email = $email;
  }

  
  /**
   * Get the password of the user
   * 
   * @return string The password of the user. 
   */
  public function getPassword(): string 
  {
    return $this->password;
  }

  /**
   * Set the password of the user.
   * @param string $password The password of the user. 
   */
  public function setPassword(string $password): void 
  {
    $this->password = $password;
  }


  /**
   * Get the role identifier of the user
   * 
   * @return int The role identifier of the user. 
   */
  public function getRoleId(): int 
  {
    return $this->roleId;
  }

  /**
   * Set the role identifier of the user.
   * @param int $roleId The role identifier of the user. 
   */
  public function setRoleId(int $roleId): void 
  {
    $this->roleId = $roleId;
  }

}