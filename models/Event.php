<?php

/**
 * Class Event
 * Defines an event in the platform.
 */
class Event 
{
  /**
   * @var int|null The unique identifier of the event. Null for a new event (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var string The title of the event.
   */
  private string $title;

  /**
   * @var string The description of the event.
   */
  private string $description;

  /**
   * @var DateTime The start date and time of the event.
   */
  private DateTime $startDate;

  /**
   * @var DateTime The end date and time of the event.
   */
  private DateTime $endDate;

  /**
   * @var string The location where the event will take place.
   */
  private string $location;

  /**
   * @var string The name of the organizer of the event.
   */
  private string $organiser;

  /**
   * @var int The number of seats available for the event.
   */
  private int $seatsAvailable;

  /**
   * @var string|null The image associated with the event. Null if not provided.
   */
  private ?string $image;

  /**
   * @var string|null The video associated with the event. Null if not provided.
   */
  private ?string $video;

  /**
   * Event constructor
   * @param int|null $id The unique identifier of the event. Null for a new event.
   * @param string $title The title of the event.
   * @param string $description The description of the event.
   * @param DateTime $startDate The start date and time of the event.
   * @param DateTime $endDate The end date and time of the event.
   * @param string $location The location where the event will take place.
   * @param string $organiser The name of the organizer of the event.
   * @param int $seatsAvailable The number of seats available for the event.
   * @param string|null $image The image associated with the event. Null if not provided.
   * @param string|null $video The video associated with the event. Null if not provided.
   */
  public function __construct(?int $id, string $title, string $description, DateTime $startDate, DateTime $endDate, string $location, string $organiser, int $seatsAvailable, ?string $image, ?string $video)
  {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->location = $location;
    $this->organiser = $organiser;
    $this->seatsAvailable = $seatsAvailable;
    $this->image = $image;
    $this->video = $video;
  }

  /**
   * Get the unique identifier of the event.
   * 
   * @return int|null The unique identifier of the event. Null for a new event.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the event.
   * @param int|null $id The unique identifier of the event.
   */
  public function setId(?int $id): void 
    {
      $this->id = $id;
    }

    
  /**
   * Get the title of the event.
   * 
   * @return string The title of the event.
   */
  public function getTitle(): string 
  {
    return $this->title;
  }

  /**
   * Set the title of the event.
   * @param string $title The title of the event.
   */
  public function setTitle(string $title): void 
  {
    $this->title = $title;
  }

    
  /**
   * Get the description of the event.
   * 
   * @return string The description of the event.
   */
  public function getDescription(): string 
  {
    return $this->description;
  }

  /**
   * Set the description of the event.
   * @param string $description The description of the event.
   */
  public function setDescription(string $description): void 
  {
    $this->description = $description;
  }

    
  /**
   * Get the start date and time of the event.
   * 
   * @return DateTime The start date and time of the event.
   */
  public function getStartDate(): DateTime 
  {
    return $this->startDate;
  }

  /**
   * Set the start date and time of the event.
   * @param DateTime $startDate The start date and time of the event.
   */
  public function setStartDate(DateTime $startDate): void 
  {
    $this->startDate = $startDate;
  }

    
  /**
   * Get the end date and time of the event.
   * 
   * @return DateTime The end date and time of the event.
   */
  public function getEndDate(): DateTime 
  {
    return $this->endDate;
  }

  /**
   * Set the end date and time of the event.
   * @param DateTime $endDate The end date and time of the event.
   */
  public function setEndDate(DateTime $endDate): void 
  {
    $this->endDate = $endDate;
  }

    
  /**
   * Get the location where the event will take place.
   * 
   * @return string The location where the event will take place.
   */
  public function getLocation(): string 
  {
    return $this->location;
  }

  /**
   * Set the location where the event will take place.
   * @param string $location The location where the event will take place.
   */
  public function setLocation(string $location): void 
  {
    $this->location = $location;
  }

    
  /**
   * Get the name of the organizer of the event.
   * 
   * @return string The name of the organizer of the event.
   */
  public function getOrganiser(): string 
  {
    return $this->organiser;
  }

  /**
   * Set the name of the organizer of the event.
   * @param string $organiser The name of the organizer of the event.
   */
  public function setOrganiser(string $organiser): void 
  {
    $this->organiser = $organiser;
  }

    
  /**
   * Get the number of seats available for the event.
   * 
   * @return int The number of seats available for the event.
   */
  public function getSeatsAvailable(): int 
  {
    return $this->seatsAvailable;
  }

  /**
   * Set the number of seats available for the event.
   * @param int $seatsAvailable The number of seats available for the event.
   */
  public function setSeatsAvailable(int $seatsAvailable): void 
  {
    $this->seatsAvailable = $seatsAvailable;
  }

    
  /**
   * Get the image associated with the event.
   * 
   * @return string|null The image associated with the event. Null if not provided.
   */
  public function getImage(): ?string 
  {
    return $this->image;
  }

  /**
   * Set the image associated with the event.
   * @param string|null $image The image associated with the event. Null if not provided.
   */
  public function setImage(?string $image): void 
  {
    $this->image = $image;
  }

    
  /**
   * Get the video associated with the event.
   * 
   * @return string|null The video associated with the event. Null if not provided.
   */
  public function getVideo(): ?string 
  {
    return $this->video;
  }

  /**
   * Set the video associated with the event.
   * @param string|null $video The video associated with the event. Null if not provided.
   */
  public function setVideo(?string $video): void 
  {
    $this->video = $video;
  }
  
}