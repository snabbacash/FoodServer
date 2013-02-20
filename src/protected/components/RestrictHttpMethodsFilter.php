<?php

/**
 * This controller filter checks that incoming requests are done with the 
 * correct methods. It also interprets the OPTIONS request which is done by 
 * AJAX clients when the client resides on another domain than the server. The 
 * server must respond to these requests with special access control headers 
 * before the client will send the actual request.
 *
 * @author Sam Stenvall <sam.stenvall@arcada.fi>
 */
class RestrictHttpMethodsFilter extends CFilter
{

	/**
	 * The value of the Access-Control-Max-Age which is sent to a client 
	 * performing an AJAX request from another domain than the server. This 
	 * should be changed to something larger (like 1 month) once this whole 
	 * thing is live.
	 */
	const CORS_REQUEST_MAX_AGE = 86400;
	
	/**
	 * @var mixed a string or array of valid request methods (e.g. PUT, POST, GET)
	 */
	public $methods;

	/**
	 * Initializes the filter
	 */
	public function init()
	{
		if (is_string($this->methods))
			$this->methods = array($this->methods);

		// OPTIONS is always a valid method, it is needed to make CORS requests
		if (!in_array('OPTIONS', $this->methods))
			$this->methods[] = 'OPTIONS';
		
		parent::init();
	}

	/**
	 * Checks the current request's method to see if it is allowed
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if the request method is invalid
	 */
	protected function preFilter($filterChain)
	{
		$requestMethod = Yii::app()->request->requestType;
		$allowedMethods = implode(', ', $this->methods);

		// Respond to OPTIONS requests with Access-Control headers
		if ($requestMethod == 'OPTIONS')
		{
			$allowedHeaders = '';

			// Always allow the headers the client requested
			if (array_key_exists('HTTP_ACCESS_CONTROL_REQUEST_HEADERS', $_SERVER))
				$allowedHeaders = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'];
			
			// Set allowed origin to whatever origin the request came from 
			// since the header can only take one value. If the origin can't be 
			// determined we use a wild-card.
			if (array_key_exists('HTTP_ORIGIN', $_SERVER))
				$allowedOrigin = $_SERVER['HTTP_ORIGIN'];
			else
				$allowedOrigin = '*';

			header('Access-Control-Allow-Origin: '.$allowedOrigin);
			header('Access-Control-Allow-Methods: '.$allowedMethods);
			header('Access-Control-Max-Age: '.self::CORS_REQUEST_MAX_AGE);
			
			if(!empty($allowedHeaders))
				header('Access-Control-Allow-Headers: '.$allowedHeaders);
			
			header('Access-Control-Allow-Credentials: true');
			Yii::app()->end();
		}
		
		if (in_array($requestMethod, $this->methods))
			return true;

		throw new CHttpException(400, 'Invalid request method. Valid request methods are '.$allowedMethods);
	}

}