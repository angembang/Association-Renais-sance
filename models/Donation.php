<?php

/**
 * Class Donation
 * Defines a donation in the platform.
 */
class Donation 
{
  /**
   * @var int|null The unique identifier of the donation. Null for a new donation (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var int|null The unique identifier of the user who made the donation. Null for anonymous donations.
   */
  private ?int $userId;

  /**
   * @var string|null the first name of the donation. Null for anonymous donations or loggged user.
   */ 
  private ?string $firstName;

   /**
   * @var string|null the last name of the donation. Null for anonymous donations or loggged user.
   */ 
  private ?string $lastName;

  /**
   * @var float The amount of the donation.
   */
  private float $amount;

  /**
   * @var DateTime The date when the donation was made.
   */
  private DateTime $donationDate;

  /**
   * @var string|null The message associated with the donation. Null if not provided.
   */
  private ?string $message;

  /**
   * @var bool Whether the donation is anonymous.
   */
  private bool $anonymous;

  /**
   * Donation constructor
   * @param int|null $id The unique identifier of the donation. Null for a new donation.
   * @param int|null $userId The unique identifier of the user who made the donation. Null for anonymous donations.
   * @param float $amount The amount of the donation.
   * @param DateTime $donationDate The date when the donation was made.
   * @param string|null $message The message associated with the donation. Null if not provided.
   * @param bool $anonymous Whether the donation is anonymous.
   * @param string|null $firstName The first name of the donation Null for anonymous donations or loggged user.
   * @param string|null $firstName The first name of the donation Null for anonymous donations or loggged user.
   */
  public function __construct(?int $id, ?int $userId, float $amount, DateTime $donationDate, ?string $message, bool $anonymous, ?string $lastName, ?string $firstName)
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->amount = $amount;
    $this->donationDate = $donationDate;
    $this->message = $message;
    $this->anonymous = $anonymous;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }

  /**
   * Get the unique identifier of the donation.
   * 
   * @return int|null The unique identifier of the donation. Null for a new donation.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the donation.
   * @param int|null $id The unique identifier of the donation.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }

    
  /**
   * Get the unique identifier of the user who made the donation.
   * 
   * @return int|null The unique identifier of the user who made the donation. Null for  */
  public function getUserId(): ?int 
  {
    return $this->userId;
  }

  /**
   * Set the unique identifier of the user who made the donation.
   * @param int|null $userId The unique identifier of the user who made the donation. Null for anonymous donations.
   */
  public function setUserId(?int $userId): void 
  {
    $this->userId = $userId;
  }

    
  /**
   * Get the amount of the donation.
   * 
   * @return float The amount of the donation.
   */
  public function getAmount(): float 
  {
    return $this->amount;
  }

  /**
   * Set the amount of the donation.
   * @param float $amount The amount of the donation.
   */
  public function setAmount(float $amount): void 
  {
    $this->amount = $amount;
  }

    
  /**
   * Get the date when the donation was made.
   * 
   * @return DateTime The date when the donation was made.
   */
  public function getDonationDate(): DateTime 
  {
    return $this->donationDate;
  }

  /**
   * Set the date when the donation was made.
   * @param DateTime $donationDate The date when the donation was made.
   */
  public function setDonationDate(DateTime $donationDate): void 
  {
    $this->donationDate = $donationDate;
  }

    
  /**
   * Get the message associated with the donation.
   * 
   * @return string|null The message associated with the donation. Null if not provided.
   */
  public function getMessage(): ?string 
  {
    return $this->message;
  }

  /**
   * Set the message associated with the donation.
   * @param string|null $message The message associated with the donation. Null if not provided.
   */
  public function setMessage(?string $message): void 
  {
    $this->message = $message;
  }

    
  /**
   * Get whether the donation is anonymous.
   * 
   * @return bool Whether the donation is anonymous.
   */
  public function isAnonymous(): bool 
  {
    return $this->anonymous;
  }

  /**
   * Set whether the donation is anonymous.
   * @param bool $anonymous Whether the donation is anonymous.
   */
  public function setAnonymous(bool $anonymous): void 
  {
    $this->anonymous = $anonymous;
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
   * Get the last name of the donation
   * 
   * @return string The last name of the donation. 
   */
  public function getLastName(): string 
  {
    return $this->lastName;
  }

  /**
   * Set the last name of the donation.
   * @param string $lastName The last name of the donation. 
   */
  public function setLastName(string $lastName): void 
  {
    $this->lastName = $lastName;
  }
  
}