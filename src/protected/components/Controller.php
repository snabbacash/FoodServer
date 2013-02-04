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
		header('Content-type: application/json');
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
		header($this->getHttpStatus($status));

		// Mimic Yii's default error messages.
		if ($status >= 400) {

			$body = (object) array(
				'status' => false,
				'message' => $body
			);
		}

		$this->renderJson($body);
	}
}
