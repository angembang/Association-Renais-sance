<?php

/**
 * Class Document
 * Defines a document in the platform.
 */
class Document 
{
  /**
   * @var int|null The unique identifier of the document. Null for a new document (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var string The name of the document.
   */
  private string $name;

  /**
   * @var string The type of the document (e.g., PDF, DOCX, etc.).
   */
  private string $type;

  /**
   * @var string The file path where the document is stored.
   */
  private string $filePath;

  /**
   * @var DateTime The date when the document was added.
   */
  private DateTime $dateAdded;

  /**
   * Document constructor
   * @param int|null $id The unique identifier of the document. Null for a new document.
   * @param string $name The name of the document.
   * @param string $type The type of the document.
   * @param string $filePath The file path where the document is stored.
   * @param DateTime $dateAdded The date when the document was added.
   */
  public function __construct(?int $id, string $name, string $type, string $filePath, DateTime $dateAdded)
  {
    $this->id = $id;
    $this->name = $name;
    $this->type = $type;
    $this->filePath = $filePath;
    $this->dateAdded = $dateAdded;
  }

  /**
   * Get the unique identifier of the document.
   * 
   * @return int|null The unique identifier of the document. Null for a new document.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the document.
   * @param int|null $id The unique identifier of the document.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }

    
  /**
   * Get the name of the document.
   * 
   * @return string The name of the document.
   */
  public function getName(): string 
  {
    return $this->name;
  }

  /**
   * Set the name of the document.
   * @param string $name The name of the document.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }


  /**
   * Get the type of the document.
   * 
   * @return string The type of the document.
   */
  public function getType(): string 
  {
    return $this->type;
  }

  /**
   * Set the type of the document.
   * @param string $type The type of the document.
   */
  public function setType(string $type): void 
  {
    $this->type = $type;
  }

    
  /**
   * Get the file path where the document is stored.
   * 
   * @return string The file path where the document is stored.
   */
  public function getFilePath(): string 
  {
    return $this->filePath;
  }

  /**
   * Set the file path where the document is stored.
   * @param string $filePath The file path where the document is stored.
   */
  public function setFilePath(string $filePath): void 
  {
    $this->filePath = $filePath;
  }

    
  /**
   * Get the date when the document was added.
   * 
   * @return DateTime The date when the document was added.
   */
  public function getDateAdded(): DateTime 
  {
    return $this->dateAdded;
  }

  /**
   * Set the date when the document was added.
   * @param DateTime $dateAdded The date when the document was added.
   */
  public function setDateAdded(DateTime $dateAdded): void 
  {
    $this->dateAdded = $dateAdded;
  }
  
}