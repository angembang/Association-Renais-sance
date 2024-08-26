<?php

/**
 * Abstract controller class providing common functionalities for controllers.
 */
abstract class AbstractController
{
    /**
     * @var \Twig\Environment $twig The Twig environment instance.
     */
    private \Twig\Environment $twig;
    
    /**
     * Constructor method to initialize the Twig environment.
     */
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader,[
            'debug' => true,
        ]);

        // Enable debugging in Twig environment
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        
        // Add CSRF token to global Twig variables
        $twig->addGlobal('csrf_token', $_SESSION["csrf-token"]);

        // Add error messages to global Twig variables
        $twig->addGlobal('error_message', isset($_SESSION["error-message"]) ? 
        $_SESSION["error-message"] : null);
        
        $this->twig = $twig;
    }
    
    /**
     * Render a Twig template with provided data.
     *
     * @param string $template The template file path.
     * @param array $data An array of data to be passed to the template.
     * @return void
     */
    protected function render(string $template, array $data) : void
    {
        echo $this->twig->render($template, $data);
    }
    
    /**
     * Render data as JSON response.
     *
     * @param array $data The data to be encoded as JSON.
     * @return void
     */
    protected function renderJson(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}