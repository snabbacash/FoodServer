<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	protected $token;
	
	/**
	 * @var StdClass the decoded JSON data from a POST request
	 */
	protected $decodedJsonData;

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
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array(
			'decodeJsonPostData',
			'requireToken',
		);
	}
	
	/**
	 * Pre-action filter which decodes the JSON from POST requests and stores 
	 * it in a property. Invalid or missing JSON triggers an exception.
	 * @param CFilterChain $filterChain
	 * @throws CHttpException
	 */
	public function filterDecodeJsonPostData($filterChain)
	{
		if (Yii::app()->request->isPostRequest)
		{
			// Read the raw POST data
			$postData = file_get_contents("php://input");

			if ($postData !== false)
			{
				$json = CJSON::decode($postData, false);
				if ($json !== null)
				{
					$this->decodedJsonData = $json;

					$filterChain->run();
				}
			}

			throw new CHttpException(400, 'Malformed JSON');
		}
		
		$filterChain->run();
	}
	
	/**
	 * Filter that checks that a valid token has been passed in the request and 
	 * if so stores it in $token
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if the token is invalid or missing
	 */
	public function filterRequireToken($filterChain)
	{
		$tokenData = '';
		
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) 
			&& $_SERVER['PHP_AUTH_USER'] == Yii::app()->params['httpUsername'])
		{
			$tokenData = $_SERVER['PHP_AUTH_PW'];
		}

		$token = UserToken::model()->findByToken($tokenData);

		if ($token !== null && $token->isValid())
		{
			// Store the token
			$this->token = $token->token;

			$filterChain->run();
		}
		else
			throw new CHttpException(401, 'Invalid token');
	}

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
		$this->setHeader('Content-Type', 'application/json');

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

	public function getSchema($schema)
	{
		$path = __DIR__ . "/../schemas/$schema.json";
		if (file_exists($path))
			return json_decode(file_get_contents($path));
		else
			throw new CHttpException(500, 'Internal error');
	}

	public function validate($schema, $request)
	{
		$validator = new JsonSchema\Validator();
		// The JsonSchema library does not conform with the latest spec.
		// Among other things, the required property should be attached to each
		// property.
		$validator->check($request, $this->getSchema($schema));
		if (!$validator->isValid())
		{
			$message = '';
			foreach ($validator->getErrors() as $error)
			{
				$message .= sprintf('[%s] %s\n', $error['property'], $error['message']);
			}
			throw new CHttpException(400, $message);
		}
		return true;
	}
}
