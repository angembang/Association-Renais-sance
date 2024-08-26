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
   * @var int The unique identifier of the sender.
   */
  private int $senderId;

  /**
   * @var string The recipients of the message, stored as a comma-separated list of recipient IDs.
   */
  private string $recipients;

  /**
   * @var string The subject of the message.
   */
  private string $subject;

  /**
   * @var string The body content of the message.
   */
  private string $body;

  /**
   * @var DateTime The date and time when the message was sent.
   */
  private DateTime $sendDate;

  /**
   * Message constructor
   * @param int|null $id The unique identifier of the message. Null for a new message.
   * @param int $senderId The unique identifier of the sender.
   * @param string $recipients The recipients of the message, stored as a comma-separated list of recipient IDs.
   * @param string $subject The subject of the message.
   * @param string $body The body content of the message.
   * @param DateTime $sendDate The date and time when the message was sent.
   */
  public function __construct(?int $id, int $senderId, string $recipients, string $subject, string $body, DateTime $sendDate)
  {
    $this->id = $id;
    $this->senderId = $senderId;
    $this->recipients = $recipients;
    $this->subject = $subject;
    $this->body = $body;
    $this->sendDate = $sendDate;
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
   * Get the unique identifier of the sender.
   * 
   * @return int The unique identifier of the sender.
   */
  public function getSenderId(): int 
  {
    return $this->senderId;
  }

  /**
   * Set the unique identifier of the sender.
   * @param int $senderId The unique identifier of the sender.
   */
  public function setSenderId(int $senderId): void 
  {
    $this->senderId = $senderId;
  }

    
  /**
   * Get the recipients of the message.
   * 
   * @return string The recipients of the message, stored as a comma-separated list of recipient IDs.
   */
  public function getRecipients(): string 
  {
    return $this->recipients;
  }

  /**
   * Set the recipients of the message.
   * @param string $recipients The recipients of the message, stored as a comma-separated list of recipient IDs.
   */
  public function setRecipients(string $recipients): void 
  {
    $this->recipients = $recipients;
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
  public function getBody(): string 
  {
    return $this->body;
  }

  /**
   * Set the body content of the message.
   * @param string $body The body content of the message.
   */
  public function setBody(string $body): void 
  {
    $this->body = $body;
  }

    
  /**
   * Get the date and time when the message was sent.
   * 
   * @return DateTime The date and time when the message was sent.
   */
  public function getSendDate(): DateTime 
  {
    return $this->sendDate;
  }

  /**
   * Set the date and time when the message was sent.
   * @param DateTime $sendDate The date and time when the message was sent.
   */
  public function setSendDate(DateTime $sendDate): void 
  {
    $this->sendDate = $sendDate;
  }
  
}