<?php

/**
 * Class Message
 * Defines a message in the platform.
 */
class Message 
{
  /**
   * @var int|null The unique identifier of the message. Null for a new message (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var string The name of the message sender.
   */
  private string $name;

  /**
   * @var string The email of the message.
   */
  private string $email;

  /**
   * @var string The subject of the message.
   */
  private string $subject;

  /**
   * @var string The body content of the message.
   */
  private string $message;

  /**
   * @var string The status of the message.
   */
  private string $status;

  /**
   * @var DateTime The date and time when the message was sent.
   */
  private DateTime $createdAt;

  /**
   * Message constructor
   * @param int|null $id The unique identifier of the message. Null for a new message.
   * @param string $name Thename of the sender.
   * @param string $email The email of the sender.
   * @param string $subject The subject of the message.
   * @param string $message The body content of the message.
   * @param string $status The status of the message.
   * @param DateTime $createdAt The date and time when the message was sent.
   */
  public function __construct(?int $id, string $name, string $email, string $subject, string $message, string $status, DateTime $createdAt)
  {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->subject = $subject;
    $this->message = $message;
    $this->status = $status;
    $this->createdAt = $createdAt;
  }

  /**
   * Get the unique identifier of the message.
   * 
   * @return int|null The unique identifier of the message. Null for a new message.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the message.
   * @param int|null $id The unique identifier of the message.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }

    
  /**
   * Get the name of the message.
   * 
   * @return string The name of the message.
   */
  public function getName(): string 
  {
    return $this->name;
  }

  /**
   * Set The name of the message.
   * @param string $name The name of the message.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }

    
  /**
   * Get the email of the message.
   * 
   * @return string The email of the message.
   */
  public function getEmail(): string 
  {
    return $this->email;
  }

  /**
   * Set the email of the message.
   * @param string $email The email of the message.
   */
  public function setEmail(string $email): void 
  {
    $this->email = $email;
  }

    
  /**
   * Get the subject of the message.
   * 
   * @return string The subject of the message.
   */
  public function getSubject(): string 
  {
    return $this->subject;
  }

  /**
   * Set the subject of the message.
   * @param string $subject The subject of the message.
   */
  public function setSubject(string $subject): void 
  {
    $this->subject = $subject;
  }

    
  /**
   * Get the body content of the message.
   * 
   * @return string The body content of the message.
   */
  public function getMessage(): string 
  {
    return $this->message;
  }

  /**
   * Set the body content of the message.
   * @param string $body The body content of the message.
   */
  public function setMessage(string $message): void 
  {
    $this->message = $message;
  }


   /**
   * Get the status of the message.
   * 
   * @return string The status of the message.
   */
  public function getStatus(): string 
  {
    return $this->status;
  }

  /**
   * Set the status of the message.
   * @param string $status The status of the message.
   */
  public function setStatus(string $status): void 
  {
    $this->status = $status;
  }

    
  /**
   * Get the date and time when the message was sent.
   * 
   * @return DateTime The date and time when the message was sent.
   */
  public function getCreatedAt(): DateTime 
  {
    return $this->createdAt;
  }

  /**
   * Set the date and time when the message was sent.
   * @param DateTime $createdAt The date and time when the message was sent.
   */
  public function setCreatedAt(DateTime $createdAt): void 
  {
    $this->createdAt = $createdAt;
  }
  
}