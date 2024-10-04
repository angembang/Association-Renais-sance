<?php

/**
 * Class PageController
 *
 * Controller class for managing page-related actions.
 */
class PageController extends AbstractController
{
  /**
   * Renders the home page.
   *
   * This method is responsible for rendering the home page view.
   *
   * @return void
   */
  public function home(): void
  {
    // Instantiate the news manager and event manager
    $newsManager = new NewsManager();
    $eventManager = new EventManager();

    // Retrieve the most recent news and event
    $latestNews = $newsManager->findLatest(); 
    $latestEvent = $eventManager->findLatest(); 

    // Check if news is found
    if (!$latestNews) {
      $this->renderJson(["success" => false, "message" => "Aucune actualité trouvée"]);
      return;
    } else if(!$latestEvent) {
      $this->renderJson(["success" => false, "message" => "Aucun événement trouvé"]);
      return;
    }
    // Render the home page with the latest news
    $this->render("home.html.twig", [
      "news" => $latestNews,
      "event"=> $latestEvent
    ]);     
  }


  /**
   * Renders the home page.
   *
   * This method is responsible for rendering the home page view.
   *
   * @return void
   */
  public function adminHome(): void
  {
    // Render the home page
    $this->render("adminHome.html.twig", []);     
  }


  /**
   * Renders the error page.
   *
   * This method is responsible for rendering the error page view.
   *
   * @return void
   */
  public function errorPage(): void
  {
    // Render the error page
    $this->render("errorPage.html.twig", []);     
  }


  /**
   * Renders the about page.
   *
   * This method is responsible for rendering the contact page view. 
   *
   * @return void
   */
  public function about(): void
  {
    // Render the about page
    $this->render("aboutPage.html.twig", []);     
  }


  /**
   * Renders the news success page.
   *
   * This method is responsible for rendering the news success page view. 
   *
   * @return void
   */
  public function  newsSuccess(): void
  { 
    // Render the news success page
    $this->render("newsSuccess.html.twig", []);     
  }


 /**
   * Renders the membership success page.
   *
   * This method is responsible for rendering the memberships success page view. 
   *
   * @return void
   */
  public function  membershipSuccess(): void
  { 
    $this->render("membershipSuccess.html.twig", []);
  } 


  /**
   * Renders the event registration success page.
   *
   * This method is responsible for rendering the event registration success page view. 
   *
   * @return void
   */
  public function  eventRegistrationSuccess(): void
  { 
    $this->render("eventRegistrationSuccess.html.twig", []);
  } 
  
  
  /*
   * cleans the url parameters
   * 
   */
  public function membershipSuccessClean(): void 
  {
    // render the success donation page 
    $this->render("membershipSuccess.html.twig", []);
  }


  
  /**
   * Convert YouTube video URL to embeddable format
   * @param string $url
   * @return string
   */
  public function convertYouTubeUrlToEmbed(string $url): string
  {
    $regex = '/https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/';
    $replace = 'https://www.youtube.com/embed/$1';
    return preg_replace($regex, $replace, $url);
  }



  /**
   * Renders the news page. 
   *
   * This method is responsible for rendering the news page view. 
   *
   * @return void
   */
  public function showNews(): void
  {
    try {
      // Instantiate the news manager
      $newsManager = new NewsManager();
      // Retrieve all news ordered by desc
      $news = $newsManager->findAll();
      if(empty($news)) {
        // Convert URLs to embed format
        foreach ($news as $newsItem) {
          if (!empty($newsItem->getVideo())) {
              $newsItem->setVideo($this->convertYouTubeUrlToEmbed($newsItem->getVideo()));
          }
      }
        $this->renderJson(["success" => false, "message" => "Actualité non trouvée"]);
        return; 
      }
      // Render the news page with neccessary data
      $this->render("newsPage.html.twig", [
        "news" => $news
      ]);

    } catch (Exception $e) {
      //error_log("An error occurred: " . $e->getMessage());
      http_response_code(500);
      $this->render("errorPage.html.twig", ["code" => 500]);
    }    
  }


  /**
   * Renders the membership page. 
   *
   * This method is responsible for rendering the membership page view. 
   *
   * @return void
   */
  public function showMemberships(): void
  { 
    try {
      // Instantiate the membership manager and role mamanger
      $membershipManager = new MembershipManager();
      $roleManager = new RoleManager();
      
      // Retrieve all memberships
      $memberships = $membershipManager->findAll();

      // Check if memberships are not empty
      if(!$memberships) {
        $this->renderJson(["success" => false, "message" => "Membres non trouvé"]);
        return;
      }
      $roles = [];
      // Loop through the memberships to retrieve roles
      foreach ($memberships as $membership) {
        $roleId = $membership->getRoleId();
          
        // Check if the role is already fetched
        if (!isset($roles[$roleId])) {
          // Retrieve the role by ID
          $role = $roleManager->findRoleById($roleId);
          if ($role) {
            $roles[$roleId] = $role->getName();
          } else {
            $roles[$roleId] = "Unknown role";
          }
        }
      }

      // Render the membershipPage with necessary data
      $this->render("membershipPage.html.twig", [
        "memberships" => $memberships,
        "roles" => $roles
      ]);

    } catch (Exception $e) {
      //error_log("An error occurred: " . $e->getMessage());
      http_response_code(500);
      $this->render("errorPage.html.twig", ["code" => 500]);
    }  
  }


  /**
   * Renders the event page. 
   *
   * This method is responsible for rendering the event page view. 
   *
   * @return void
   */
  public function showEvents(): void
  {     
  }


  /**
   * Renders the event registration page. 
   *
   * This method is responsible for rendering the event registration page view. 
   *
   * @return void
   */
  public function showEventRegistrations(): void
  {     
  }


  /*
   *
   *
   */
  public function showNewsById(): void 
  { 
    try {
      // Vérifiez si 'news_id' est présent dans l'URL
      if (!isset($_GET["id"])) {
        throw new Exception("news_id non spécifié");
    }

    $newsId = $_GET["id"];
    $newsManager = new NewsManager();
    $news = $newsManager->findNewsById($newsId);
    //error_log("Recherche d'actualité avec ID : " . $newsId);

    if ($news) {
        $this->render("newsDetail.html.twig", [
            "news" => $news
        ]);
    } else {
        throw new Exception("news non trouvé");
    }

    } catch (Exception $e) {
      //error_log("An error occurred: " . $e->getMessage());
      http_response_code(500);
      $this->render("errorPage.html.twig", ["code" => 500]);
    }  
  }


  /**
   * Renders the legacy policy page. 
   *
   * This method is responsible for rendering the legacy policy page view. 
   *
   * @return void
   */
  public function legacyPlolicy(): void 
  {
    $this->render("legacyPolicy.html.twig", []);
  }
    
}