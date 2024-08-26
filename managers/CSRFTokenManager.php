<?php

/**
 * Class CSRFTokenManager
 *
 * Manages CSRF tokens for form submission protection.
 */
class CSRFTokenManager
{
    /**
     * Generates a CSRF token.
     *
     * @return string The generated CSRF token.
     */
    public function generateCSRFToken(): string
    {
        $token = bin2hex(random_bytes(32));
        
        return $token;
    }
    
    
    /**
     * Validates a CSRF token.
     *
     * @param mixed $token The token to validate.
     * @return bool True if the token is valid, false otherwise.
     */
    public function validateCSRFToken($token): bool
    {
        if(isset($_POST["csrf-token"]) && hash_equals($_SESSION["csrf-token"], $token))
        {
            return true;
        } else
        {
            return false;
        }
    }
}