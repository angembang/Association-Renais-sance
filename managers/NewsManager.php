<?php

/**
 * Manages the retrieval and persistence of New object in the platform
 */
class NewsManager extends AbstractManager
{
  /**
   * creates a new new and persists it in the database.
   * 
   * @param News $new The news object to be created.
   * 
   * @return News The created news object with the assigned identifier. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createNews(News $news): News
  {
    try {
      // Prepare the SQL query to insert a new news into the database
      $query = $this->db->prepare("INSERT INTO news (title, content, image, video, publication_date, update_date, excerpt) 
      VALUES (:title, :content, :image, :video, :publication_date, :update_date, :excerpt)");

      // Bind the parameters with their values.
      $parameters = [
        ":title" => $news->getTitle(),
        ":content" =>$news->getContent(),
        ":image" => $news->getImage(),
        "video" => $news->getVideo(),
        ":publication_date" => $news->getPublicationDate(),
        ":update_date" => $news->getUpdateDate(),
        ":excerpt" => $news->getExcerpt()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $newsId = $this->db->lastInsertId();

      // Set the identifier for the created news
      $news->setId($newsId);

      // Return the created news object
      return $news;
    
    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a news" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a news");
    }
  }
  


  /**
   * Retrieves an news by its unique identifier
   * 
   * @param int $newsId The unique identifier of the news
   * 
   * @return News|null The retrieved news or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findNewsById(int $newsId): ?News
  {
    try {
      // Prepare the SQL query to retrieve the news by its unique identifier
      $query = $this->db->prepare("SELECT * FROM news WHERE id = :id");
    
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $newsId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the news data from the database
      $newsData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if news is found
      if ($newsData) {
        return $this->hydrateNews($newsData); 
      } 
      return null;

    } catch (PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to find a news:" .$e->getMessage(). $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to find a news");
    }     
  }


  /**
   * Retrieves news by its publication date
   * 
   * @param DateTime $publicationDate The publication date of the news.
   * 
   * @return array|null The retrieved news or null if no news is found. 
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findNewsByPublicationDate(DateTime $publicationDate): ?array
  {
    try {
      // Prepare the SQL query to retrieve news by their publication date.
      $query = $this->db->prepare("SELECT * FROM news WHERE publication_date= :publication_date");

      // Bind the parameter with its value.
      $parameter = [
        ":publication_date" => $publicationDate
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch news data from the database
      $actualitiesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if news data is not empty
      if($actualitiesData) {
        $actualities = [];
        // Loop through each actualitiesdata
        foreach($actualitiesData as $newsData) {
          // Instantiate an news for each news data
          $news = new News(
            $newsData["id"],
            $newsData["title"],
            $newsData["content"],
            $newsData["image"],
            $newsData["video"],
            $newsData["publication_date"],
            $newsData["update_date"],
            $newsData["excerpt"]
          );
          // Add the instantiated news object to the news array
          $actualities[] = $news;
        }
        // Return the array of the news objects
        return $actualities;
      }
      // No news is found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find  news" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find news");
    }  
  }


  /**
   * Retrieves all news
   *
   * @return array|null The array of news or null if no news is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all news into the database
      $query = $this->db->prepare("SELECT * FROM news");

      // Execute the query
      $query->execute();

      // Fetch news data from the database
      $actualitiesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if news data is not empty
      if($actualitiesData) {
        $actualities = [];
        // Loop through each newsdata
        foreach($actualitiesData as $newsData) {
          // Instantiate an news for each news data
          $news = new News(
            $newsData["id"],
            $newsData["title"],
            $newsData["content"],
            $newsData["image"],
            $newsData["video"],
            $newsData["publication_date"],
            $newsData["update_date"],
            $newsData["excerpt"]
          );
          // Add the instantiated news object to the news array
          $actualities[] = $news;
        }
        // Return the array of the news objects
        return $actualities;
      }
      // No news is found, return null.
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find news:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find news");
    }  
  }


  /**
   * Updates an news in the database
   * 
   * @param News $news The news to be updated.
   * 
   * @return News|null The news updated or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updatNews(News $news): ?News
  {
    try {
      // Prepare the SQL query to update an news.
      $query = $this->db->prepare("UPDATE news SET 
      title = :title,
      content = :content,
      image = :image,
      video = :video,
      publication_date = :publication_date,
      update_date = :update_date,
      excerpt = :excerpt
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $news->getId(),
        ":title" => $news->getTitle(),
        ":content" => $news->getContent(),
        ":image" => $news->getImage(),
        ":video" => $news->getVideo(),
        "publication_date" => $news->getPublicationDate(),
        "update_date" => $news->getUpdateDate(),
        "excerpt" =>$news->getExcerpt()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $news;
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the news:" .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the news");
    }  
  }
  
  
  /**
   * Deletes an news from the database
   * 
   * @param int $newsId The unique identifier of the news to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteNewsById(int $newsId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve news.
      $query = $this->db->prepare("DELETE FROM news WHERE id = :id");

      // Bind the parameter its value
      $parameters = [
        ":id" => $newsId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
      
    } catch(PDOException $e) {
      error_log("Failed to delete the news: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the news");
    }  
  }


  /**
     * Helper method to hydrate News object from data.
     * 
     * @param $newsData The data of the news retrieve from the database.
     * 
     * @return News The retrieved news
     */
    private function hydrateNews($newsData): News
    {
      // Instantiate a new news with retrieved data
      $news = new News(
        $newsData["id"],
        $newsData["title"],
        $newsData["content"],
        $newsData["image"],
        $newsData["video"],
        $newsData["publication_date"],
        $newsData["update_date"],
        $newsData["excerpt"]
        
      );
      return $news;
    }
}