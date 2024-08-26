<?php

/**
 * Class Role
 * Defines a role in the platform.
 */
class Role 
{
  /**
   * @var int|null The unique identifier of the role. Null for a new role (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var string The name of the role.
   */
  private string $name;

  /**
   * @var string|null The description of the role, null if not define.
   */
  private ?string $description;

  /**
   * Role constructor
   * @param int|null $id The unique identifier of the role. Null for a new role.
   * @param string $name The name of the role.
   * @param string|null $description The description of the role.
   */
  public function __construct(?int $id, string $name, ?string $description)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
  }

  /**
   * Get the unique identifier of the role.
   * 
   * @return int|null The unique identifier of the role. Null for a new role.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the role.
   * @param int|null $id The unique identifier of the role.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }

    
  /**
   * Get the name of the role.
   * 
   * @return string The name of the role.
   */
  public function getName(): string 
  {
    return $this->name;
  }

  /**
   * Set the name of the role.
   * @param string $name The name of the role.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }


  /**
   * Get the description of the role.
   * 
   * @return string|null The description of the role, null if not define.
   */
  public function getDescription(): ?string 
  {
    return $this->description;
  }

  /**
   * Set the description of the role.
   * @param string $description The description of the role.
   */
  public function setDescription(?string $description): void 
  {
    $this->description = $description;
  }
  
}