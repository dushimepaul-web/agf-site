<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Exceptions : les erreurs PHP (warning, exception, error) doivent produire
 * un JSON propre pour les requêtes API/AJAX — jamais de page HTML dans une API.
 *
 * En environnement development, show_php_error() est appelé pour chaque
 * warning/notice par le gestionnaire d'erreurs de CodeIgniter : si la réponse
 * était une API, on renvoie un objet JSON au lieu de la page HTML.
 */
class MY_Exceptions extends CI_Exceptions
{
    private function _is_api_request()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        if (strpos($uri, '/api/') !== false) return true;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') return true;
        return false;
    }

    private function _json_error($message, $status_code)
    {
        $status_code = (int)$status_code;
        if (!headers_sent()) {
            @http_response_code($status_code);
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode(array(
            'success' => false,
            'message' => $message,
            'status' => $status_code
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function show_php_error($severity, $message, $filepath, $line)
    {
        if ($this->_is_api_request()) {
            // Le détail de l'erreur n'est exposé qu'en développement.
            if (ENVIRONMENT === 'development') {
                $message = 'Erreur interne du serveur | ' . $message . ' @ ' . basename($filepath) . ':' . $line;
            } else {
                $message = 'Erreur interne du serveur';
            }
            $this->_json_error($message, 500);
            return;
        }
        return parent::show_php_error($severity, $message, $filepath, $line);
    }

    public function show_exception($exception)
    {
        if ($this->_is_api_request()) {
            if (ENVIRONMENT === 'development') {
                $message = 'Erreur interne du serveur | ' . get_class($exception) . ': ' . $exception->getMessage() . ' @ ' . $exception->getFile() . ':' . $exception->getLine();
            } else {
                $message = 'Erreur interne du serveur';
            }
            $this->_json_error($message, 500);
            return;
        }
        return parent::show_exception($exception);
    }

    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        if ($this->_is_api_request()) {
            $this->_json_error('Erreur interne du serveur', $status_code);
            return;
        }
        return parent::show_error($heading, $message, $template, $status_code);
    }
}