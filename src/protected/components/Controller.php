<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	private $statusCodes = array(
		200 => 'OK',
		201 => 'Created',
		400 => 'Bad Request',
		401 => 'Not Authorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		500 => 'Internal Server Error',
	);

	/**
	 * Render JSON to the client.
	 *
	 * @param mixed $data the data to render as JSON.
	 */
	public function renderJson($data)
	{
		$this->layout = false;

		$this->setHeader('Access-Control-Allow-Origin', '*');
		$this->setHeader('Access-Control-Allow-Headers', 'Authorization');
		$this->setHeader('Content-type:', 'application/json');

		echo CJSON::encode($data);
		Yii::app()->end();
	}

	/**
	 * Return a http status header from the code.
	 *
	 * @param int $status http status code.
	 * @returns string
	 */
	public function getHttpStatus($status=200)
	{
		return 'HTTP/1.1 ' . $status . ' ' . $this->statusCodes[$status];
	}

	/**
	 * Specify which HTTP methods are allowed
	 *
	 * @param mixed $methods array or comma separated string of methods.
	 */
	public function allow($methods)
	{
		if (is_array($methods))
			$methods = implode(', ', $methods);

		$this->setHeader('Access-Control-Allow-Methods', $methods);
	}

	/**
	 * Set a header field.
	 *
	 * @param string $key field key.
	 * @param string $value field value.
	 */
	public function setHeader($key, $value=null)
	{
		// Doesn't have a key
		if (is_null($value))
			header($key);
		else
			header("$key: $value");
	}

	/**
	 * Put data is accessed through stdin
	 * @see http://php.net/manual/en/features.file-upload.put-method.php
	 */
	public function getPutData()
	{
		parse_str(file_get_contents('php://input'), $data);
		return $data;
	}

	/**
	 * Validate auth token and return 401 Not Authorized if invalid.
	 */
	public function checkAuth()
	{
		if (!isset($_SERVER['PHP_AUTH_PW']))
			return $this->sendResponse(401);

		$token = $_SERVER['PHP_AUTH_PW'];
		if (!$token)
			return $this->sendResponse(401, 'Invalid token');
	}

	/**
	 * Send a JSON to the client.
	 * If the status code is higher than 400, the body will be an object with the
	 * status property of false and the body contained in a message property.
	 *
	 * @param int $status http status code. If ommitted the code 200 will be used.
	 * @param mixed $body the content to send back to the client.
	 */
	public function sendResponse($status=200, $body='')
	{
		// Default status code to 200.
		if (!is_int($status)) {
			$body = $status;
			$status = 200;
		}
		$this->setHeader($this->getHttpStatus($status));

		// Mimic Yii's default error messages.
		if ($status >= 400) {

			// Default error messages to HTTP Status.
			if (empty($body))
				$body = $this->statusCodes[$status];

			$body = (object) array(
				'status' => false,
				'message' => $body
			);
		}

		$this->renderJson($body);
	}
}
