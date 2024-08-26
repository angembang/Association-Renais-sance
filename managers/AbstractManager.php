<?php

/**
 * Class AbstractManager
 * Abstract class to provide database connection and common methods for other managers.
 */
abstract class AbstractManager 
{
  /**
   *  @var PDO the PDO instance for database connection.
   */
  protected PDO $db;

  /**
   * AbstractManager constructor
   * Initialize the database connection using the provided environment variables.
   */ 
  public function __construct()
  {
    // Construct the PDO connection string using environment variables.
    $connexion = "mysql:host=".$_ENV["DB_HOST"].";port=3306;charset=".$_ENV["DB_CHARSET"]."; dbname=".$_ENV["DB_NAME"];

    // Initialize the PDO instance for database connection.
    $this->db = new PDO(
      $connexion,
      $_ENV["DB_USER"],
      $_ENV["DB_PASSWORD"]
    );
  } 
  
}
