<?php

/**
 * Class EventRegistration
 * Defines a registration for an event in the platform.
 */
class EventRegistration 
{
  /**
   * @var int|null The unique identifier of the registration. Null for a new registration (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var int The unique identifier of the event.
   */
  private int $eventId;

  /**
   * @var int|null The unique identifier of the membership. Null if the user is not a member
   */
  private ?int $membershipId;

  /**
   * @var DateTime The date and time when the registration was made.
   */
  private DateTime $registrationDate;

  /**
   * @var string|null The first name of the registrant. Null if not provided.
   */
  private ?string $firstName;

  /**
   * @var string|null The last name of the registrant. Null if not provided.
   */
  private ?string $lastName;

  /**
   * EventRegistration constructor
   * @param int|null $id The unique identifier of the registration. Null for a new registration.
   * @param int $eventId The unique identifier of the event.
   * @param int|null $membershipId The unique identifier of the membership. Null if the user is not a member.
   * @param DateTime $registrationDate The date and time when the registration was made.
   * @param string|null $firstName The first name of the registrant. Null if not provided.
   * @param string|null $lastName The last name of the registrant. Null if not provided.
   */
  public function __construct(?int $id, int $eventId, ?int $membershipId, DateTime $registrationDate, ?string $firstName, ?string $lastName)
  {
    $this->id = $id;
    $this->eventId = $eventId;
    $this->membershipId = $membershipId;
    $this->registrationDate = $registrationDate;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }

  /**
   * Get the unique identifier of the registration.
   * 
   * @return int|null The unique identifier of the registration. Null for a new registration.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the registration.
   * @param int|null $id The unique identifier of the registration.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }

    
  /**
   * Get the unique identifier of the event.
   * 
   * @return int The unique identifier of the event.
   */
  public function getEventId(): int 
  {
    return $this->eventId;
  }

  /**
   * Set the unique identifier of the event.
   * @param int $eventId The unique identifier of the event.
   */
  public function setEventId(int $eventId): void 
  {
    $this->eventId = $eventId;
  }


  /**
   * Get the unique identifier of the membership.
   * 
   * @return int|null The unique identifier of themembership. Null if the user is not a member.
   */
  public function getMembershipId(): ?int 
  {
    return $this->membershipId;
  }

  /**
   * Set the unique identifier of the membership
   * @param int|null $userId The unique identifier of the membership Null if the user is nota member.
   */
  public function setMembershipId(?int $membershipId): void 
  {
    $this->membershipId = $membershipId;
  }

  
  /**
   * Get the date and time when the registration was made.
   * 
   * @return DateTime The date and time when the registration was made.
   */
  public function getRegistrationDate(): DateTime 
  {
    return $this->registrationDate;
  }

  /**
   * Set the date and time when the registration was made.
   * @param DateTime $registrationDate The date and time when the registration was made.
   */
  public function setRegistrationDate(DateTime $registrationDate): void 
  {
    $this->registrationDate = $registrationDate;
  }

    
  /**
   * Get the first name of the registrant.
   * 
   * @return string|null The first name of the registrant. Null if not provided.
   */
  public function getFirstName(): ?string 
  {
    return $this->firstName;
  }

  /**
   * Set the first name of the registrant.
   * @param string|null $firstName The first name of the registrant. Null if not provided.
   */
  public function setFirstName(?string $firstName): void 
  {
    $this->firstName = $firstName;
  }

    
  /**
   * Get the last name of the registrant.
   * 
   * @return string|null The last name of the registrant. Null if not provided.
   */
  public function getLastName(): ?string 
  {
    return $this->lastName;
  }

  /**
   * Set the last name of the registrant.
   * @param string|null $lastName The last name of the registrant. Null if not provided.
   */
  public function setLastName(?string $lastName): void 
  {
    $this->lastName = $lastName;
  }
  
}