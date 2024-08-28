<?php

/**
 * Class News
 * Defines a news article in the platform.
 */
class News 
{
  /**
   * @var int|null The unique identifier of the news article. Null for a new article (not yet stored in the database).
   */
  private ?int $id;

  /**
   * @var string The title of the news article.
   */
  private string $title;

  /**
   * @var string the excerpt of the news article.
   */
  private string $excerpt;

  /**
   * @var string The content of the news article.
   */
  private string $content;

  /**
   * @var string|null The image associated with the news article. Null if no image is provided.
   */
  private ?string $image;

  /**
   * @var string|null The video associated with the news article. Null if no video is provided.
   */
  private ?string $video;

  /**
   * @var DateTime The publication date of the news article.
   */
  private DateTime $publicationDate;

  /**
   * @var DateTime|null The update date of the news article. Null if the article has not been updated.
   */
  private ?DateTime $updateDate;

  /**
   * News constructor
   * @param int|null $id The unique identifier of the news article. Null for a new article.
   * @param string $title The title of the news article.
   * @param string $excerpt the excerpt of the news article.
   * @param string $content The content of the news article.
   * @param string|null $image The image associated with the news article. Null if no image is provided.
   * @param string|null $video The video associated with the news article. Null if no video is provided.
   * @param DateTime $publicationDate The publication date of the news article.
   * @param DateTime|null $updateDate The update date of the news article. Null if the article has not been updated.
   */
  public function __construct(?int $id, string $title, string $content, ?string $image, ?string $video, DateTime $publicationDate, ?DateTime $updateDate, string $excerpt)
  {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->image = $image;
    $this->video = $video;
    $this->publicationDate = $publicationDate;
    $this->updateDate = $updateDate;
    $this->excerpt = $excerpt;
  }

  /**
   * Get the unique identifier of the news article.
   * 
   * @return int|null The unique identifier of the news article. Null for a new article.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the news article.
   * @param int|null $id The unique identifier of the news article.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }

  
  /**
   * Get the title of the news article.
   * 
   * @return string The title of the news article.
   */
  public function getTitle(): string 
  {
    return $this->title;
  }

  /**
   * Set the title of the news article.
   * @param string $title The title of the news article.
   */
  public function setTitle(string $title): void 
  {
    $this->title = $title;
  }

    
  /**
   * Get the content of the news article.
   * 
   * @return string The content of the news article.
   */
  public function getContent(): string 
  {
    return $this->content;
  }

  /**
   * Set the content of the news article.
   * @param string $content The content of the news article.
   */
  public function setContent(string $content): void 
  {
    $this->content = $content;
  }

    
  /**
   * Get the image associated with the news article.
   * 
   * @return string|null The image associated with the news article. Null if no image is provided.
   */
  public function getImage(): ?string 
  {
    return $this->image;
  }

  /**
   * Set the image associated with the news article.
   * @param string|null $image The image associated with the news article. Null if no image is provided.
   */
  public function setImage(?string $image): void 
  {
    $this->image = $image;
  }

    
  /**
   * Get the video associated with the news article.
   * 
   * @return string|null The video associated with the news article. Null if no video is provided.
   */
  public function getVideo(): ?string 
  {
    return $this->video;
  }

  /**
   * Set the video associated with the news article.
   * @param string|null $video The video associated with the news article. Null if no video is provided.
   */
  public function setVideo(?string $video): void 
  {
    $this->video = $video;
  }

  
  /**
  * Get the publication date of the news article.
   * 
   * @return DateTime The publication date of the news article.
   */
  public function getPublicationDate(): DateTime 
  {
    return $this->publicationDate;
  }

  /**
   * Set the publication date of the news article.
   * @param DateTime $publicationDate The publication date of the news article.
   */
  public function setPublicationDate(DateTime $publicationDate): void 
  {
    $this->publicationDate = $publicationDate;
  }

    
  /**
   * Get the update date of the news article.
   * 
   * @return DateTime|null The update date of the news article. Null if the article has not been updated.
   */
  public function getUpdateDate(): ?DateTime 
  {
    return $this->updateDate;
  }

  /**
   * Set the update date of the news article.
   * @param DateTime|null $updateDate The update date of the news article. Null if the article has not been updated.
   */
  public function setUpdateDate(?DateTime $updateDate): void 
  {
    $this->updateDate = $updateDate;
  }


  /**
   * Get the excerpt of the news article.
   * 
   * @return string The excerpt of the news article. 
   */
  public function getExcerpt(): string
  {
    return $this->excerpt;
  }

  /**
   * Set the excerpt of the news article.
   * @param string $updateDate The excerpt of the news article. 
   */
  public function setExcerpt(string $excerpt): void 
  {
    $this->excerpt = $excerpt;
  }


}