<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extension de CI_Security : validation CSRF pour les corps JSON.
 *
 * CodeIgniter ne lit que $_POST pour vérifier le jeton CSRF. Or l'application
 * envoie ses POST JSON via api.js avec le jeton dans le corps JSON
 * (Content-Type: application/json) : $_POST reste vide et la vérification
 * échouait systématiquement en 403.
 */
class MY_Security extends CI_Security
{
	public function csrf_verify()
	{
		if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'
			&& empty($_POST)
			&& isset($_SERVER['CONTENT_TYPE'])
			&& strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
		{
			$raw = file_get_contents('php://input');
			if ($raw !== false && $raw !== '')
			{
				// Tolérance encodage UTF-8 BOM
				if (substr($raw, 0, 3) === "\xEF\xBB\xBF")
				{
					$raw = substr($raw, 3);
				}
				$data = json_decode($raw, true);
				if (is_array($data)
					&& isset($data[$this->_csrf_token_name])
					&& is_string($data[$this->_csrf_token_name]))
				{
					// Le parent supprime la clé de $_POST après vérification.
					$_POST[$this->_csrf_token_name] = $data[$this->_csrf_token_name];
				}
			}
		}

		return parent::csrf_verify();
	}
}
