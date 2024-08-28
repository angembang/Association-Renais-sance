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
   * @var string the phone number of the user. 
   */ 
  private string $phone;
  
  /**
   * @var string the password of the user. 
   */
  private string $password;
  
  /**
   * @var int the role identifier of the user. 
   */
   private int $roleId;
  
   /**
   * @var string the address of the user. 
   */
  private string $address;

  /**
   * @var DateTime the membership date of the user. 
   */ 
  private DateTime $membershipDate;

   /**
   * @var string the civility of the user. 
   */
  private string $civility;
  

  /**
   * User constructor
   * @param int|null $id The unique identifier of the user. Null for a new user.
   * @param string $firstName The first name of the user.
   * @param string $lastName The last name of the user.
   * @param string $email The email of the user.
   * @param string $phone The phone number of the user.
   * @param string $password The password of the user.
   * @param int $roleId The role identifier of the user.
   * @param string $address The address of the user.
   * @param DateTime $membershipDate The membership date of the user.
   * @param string $civility The civility of the user.
   */
  public function __construct(?int $id, string $lastName, string $firstName, string $email, string $phone, string $address, DateTime $membershipDate, string $civility, int $roleId, string $password)
  {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->email = $email;
    $this->phone = $phone;
    $this->password = $password;
    $this->roleId = $roleId;
    $this->address = $address;
    $this->membershipDate = $membershipDate;
    $this->civility = $civility;
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
   * Get the phone number of the user
   * 
   * @return string The phone number of the user. 
   */
  public function getPhone(): string 
  {
    return $this->phone;
  }

  /**
   * Set the phone number of the user.
   * @param string $phone The phone number of the user. 
   */
  public function setPhone(string $phone): void 
  {
    $this->phone = $phone;
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


  /**
   * Get the address of the user
   * 
   * @return string The address of the user. 
   */
  public function getAddress(): string 
  {
    return $this->address;
  }

  /**
   * Set the address of the user.
   * @param string $address The address of the user. 
   */
  public function setAddress(string $address): void 
  {
    $this->address = $address;
  }


   /**
   * Get the membership date of the user
   * 
   * @return DateTime The membership date of the user. 
   */
  public function getMembershipDate(): DateTime
  {
    return $this->membershipDate;
  }

  /**
   * Set the membership date of the user.
   * @param DateTime $membershipDate The membership date of the user. 
   */
  public function setMembershipDate(DateTime $membershipDate): void 
  {
    $this->membershipDate= $membershipDate;
  }


   /**
   * Get the civility of the user
   * 
   * @return string The civility of the user. 
   */
  public function getCivility(): string 
  {
    return $this->civility;
  }

  /**
   * Set the civility of the user.
   * @param string $civility The civility of the user. 
   */
  public function setCivility(string $civility): void 
  {
    $this->civility = $civility;
  }


}