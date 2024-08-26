<?php

/**
 * Class Resource
 * Defines a resource in the platform.
 */
class Resource 
{
  /**
   * @var int|null The unique identifier of the resource. Null for a new resource (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var string The name of the resource.
   */
  private string $name;

  /**
   * @var string The type of the resource.
   */
  private string $type;

  /**
   * @var string The description of the resource.
   */
  private string $description;

  /**
   * @var int The quantity of the resource available.
   */
  private int $quantity;

  /**
   * Resource constructor
   * @param int|null $id The unique identifier of the resource. Null for a new resource.
   * @param string $name The name of the resource.
   * @param string $type The type of the resource.
   * @param string $description The description of the resource.
   * @param int $quantity The quantity of the resource available.
   */
  public function __construct(?int $id, string $name, string $type, string $description, int $quantity)
  {
    $this->id = $id;
    $this->name = $name;
    $this->type = $type;
    $this->description = $description;
    $this->quantity = $quantity;
  }

  /**
   * Get the unique identifier of the resource.
   * 
   * @return int|null The unique identifier of the resource. Null for a new resource.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the resource.
   * @param int|null $id The unique identifier of the resource.
  */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the name of the resource.
   * 
   * @return string The name of the resource.
   */
  public function getName(): string 
  {
    return $this->name;
  }

  /**
   * Set the name of the resource.
   * @param string $name The name of the resource.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }

    
  /**
   * Get the type of the resource.
   * 
   * @return string The type of the resource.
   */
  public function getType(): string 
  {
    return $this->type;
  }

  /**
   * Set the type of the resource.
   * @param string $type The type of the resource.
   */
  public function setType(string $type): void 
  {
    $this->type = $type;
  }

    
  /**
   * Get the description of the resource.
   * 
   * @return string The description of the resource.
   */
  public function getDescription(): string 
  {
    return $this->description;
  }

  /**
   * Set the description of the resource.
   * @param string $description The description of the resource.
   */
  public function setDescription(string $description): void 
  {
    $this->description = $description;
  }


  /**
   * Get the quantity of the resource available.
   * 
   * @return int The quantity of the resource available.
   */
  public function getQuantity(): int 
  {
    return $this->quantity;
  }

  /**
   * Set the quantity of the resource available.
   * @param int $quantity The quantity of the resource available.
   */
  public function setQuantity(int $quantity): void 
  {
    $this->quantity = $quantity;
  }
  
}