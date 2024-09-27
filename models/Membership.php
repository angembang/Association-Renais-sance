<?php

/** 
 * Class Membership
 * Defines an membership in the plateform.
 */
class Membership 
{
  /**
   * @var int|null the unique identifier of themembership. Null for a new membership (not yet store in the database) 
   */ 
  private ?int $id;

  /**
   * @var string the civility of the membership. 
   */
  private string $civility;

  /**
   * @var int the role identifier of the membership. 
   */
  private int $roleId;

  /**
   * @var string the first name of the membership. 
   */ 
  private string $firstName;

   /**
   * @var string the last name of the membership. 
   */ 
  private string $lastName;
  
  /**
   * @var string the email of the membership. 
   */ 
  private string $email;

  /**
   * @var string the phone number of the membership. 
   */ 
  private string $phone;
  
  /**
   * @var string the address of the membership. 
   */
  private string $address;

  /**
   * @var string the postal code of the membership. 
   */
  private string $postalCode;

  /**
   * @var string the membership created date. 
   */ 
  private string $createdAt;

  /**
   * @var string|null the logo of the membership. Null if not provided.
   */
  private ?string $logo;

  /**
   * @var string|null the company name of the membership. Null if not provided.
   */
  private ?string $companyName;
  

  /**
   * User constructor
   * @param int|null $id The unique identifier of the membership. Null for a new membership.
   *  @param string $civility The civility of the membership.
   * @param int $roleId The role identifier of the membership.
   * @param string $firstName The first name of the membership.
   * @param string $lastName The last name of themembership.
   * @param string $email The email of the membership.
   * @param string $phone The phone number of the membership.
   * @param string $address The address of the membership.
   * @param string $postalCode The postal code of the membership.
   * @param string $createdAt The membership created date.
   * @param string|null $logo the logo of the membership. Null if not provided.
   * @param string|null $companyName the company name of the membership. Null if not provided.
   *
   */
  public function __construct(?int $id, string $civility, int $roleId, string $firstName, string $lastName, string $email, string $phone, string $address, string $postalCode, string $createdAt,?string $logo, ?string $companyName)
  {
    $this->id = $id;
    $this->civility = $civility;
    $this->roleId = $roleId;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->email = $email;
    $this->phone = $phone; 
    $this->address = $address;
    $this->postalCode = $postalCode;
    $this->createdAt = $createdAt;
    $this->logo = $logo;
    $this->companyName = $companyName;
    
  }

  
  /**
   * Get the unique identifier of the membership
   * 
   * @return int|null The unique identifier of the membership. Null for a new membership.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the membership.
   * @param int|null $id The unique identifier of the membership. 
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the first name of the membership
   * 
   * @return string The first name of the membership. 
   */
  public function getFirstName(): string 
  {
    return $this->firstName;
  }

  /**
   * Set the first name of the membership.
   * @param string $firstName The first name of the membership. 
   */
  public function setFirstName(string $firstName): void 
  {
    $this->firstName = $firstName;
  }


  /**
   * Get the last name of the membership
   * 
   * @return string The last name of the membership. 
   */
  public function getLastName(): string 
  {
    return $this->lastName;
  }

  /**
   * Set the last name of the membership.
   * @param string $lastName The last name of the membership. 
   */
  public function setLastName(string $lastName): void 
  {
    $this->lastName = $lastName;
  }



  /**
   * Get the email of the membership
   * 
   * @return string The email of the membership. 
   */
  public function getEmail(): string 
  {
    return $this->email;
  }

  /**
   * Set the email of the membership.
   * @param string $email The email of the membership. 
   */
  public function setEmail(string $email): void 
  {
    $this->email = $email;
  }


   /**
   * Get the phone number of the membership
   * 
   * @return string The phone number of the membership. 
   */
  public function getPhone(): string 
  {
    return $this->phone;
  }

  /**
   * Set the phone number of the membership.
   * @param string $phone The phone number of the membership. 
   */
  public function setPhone(string $phone): void 
  {
    $this->phone = $phone;
  }
  
  
  /**
   * Get the role identifier of the membership
   * 
   * @return int The role identifier of the membership. 
   */
  public function getRoleId(): int 
  {
    return $this->roleId;
  }

  /**
   * Set the role identifier of the membership.
   * @param int $roleId The role identifier of the membership. 
   */
  public function setRoleId(int $roleId): void 
  {
    $this->roleId = $roleId;
  }


  /**
   * Get the address of the membership
   * 
   * @return string The address of the membership. 
   */
  public function getAddress(): string 
  {
    return $this->address;
  }

  /**
   * Set the address of the membership.
   * @param string $address The address of the membership. 
   */
  public function setAddress(string $address): void 
  {
    $this->address = $address;
  }


   /**
   * Get the postal code of the membership
   * 
   * @return string The postal code of the membership. 
   */
  public function getPostalCode(): string 
  {
    return $this->postalCode;
  }

  /**
   * Set the postal code of the membership.
   * @param string $postalcode The postal code of the membership. 
   */
  public function setPostalCode(string $postalCode): void 
  {
    $this->postalCode = $postalCode;
  }


   /**
   * Get the membership created date 
   * 
   * @return string The membership created date. 
   */
  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  /**
   * Set the membership created date.
   * @param string $createdAt The membership created date. 
   */
  public function setCreatedAt(string $createdAt): void 
  {
    $this->createdAt = $createdAt;
  }


   /**
   * Get the civility of the membership
   * 
   * @return string The civility of the membership. 
   */
  public function getCivility(): string 
  {
    return $this->civility;
  }

  /**
   * Set the civility of the membership.
   * @param string $civility The civility of the membership. 
   */
  public function setCivility(string $civility): void 
  {
    $this->civility = $civility;
  }


  /**
   * Get the civility of the membership
   * 
   * @return string|null The logo of the membership. Null if not provided. 
   */
  public function getLogo(): ?string 
  {
    return $this->logo;
  }
  
  /**
   * Set the logo of the membership.
   * @param ?string $logo The civility of the membership. 
   */
  public function setLogo(?string $logo): void 
  {
    $this->logo = $logo;
  }


  /**
   * Get the company name of the membership
   * @return string|null The company name of the membership. Null if not provided. 
   */
  public function getCompanyName(): ?string 
  {
    return $this->companyName;
  }
  
  /**
   * Set the company name of the membership.
   * @param ?string $companyName The company name of the membership. 
   */
  public function setCompanyName(?string $companyName): void 
  {
    $this->companyName = $companyName;
  }


}