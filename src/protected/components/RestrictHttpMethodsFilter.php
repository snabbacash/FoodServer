<?php

/**
 * Description of RestrictHttpMethodsFilter
 *
 * @author sam
 */
class RestrictHttpMethodsFilter extends CFilter
{

	/**
	 * @var mixed a string or array of valid request types (e.g. PUT, POST, GET)
	 */
	public $methods;

	/**
	 * Initializes the filter
	 */
	public function init()
	{
		if (is_string($this->methods))
			$this->methods = array($this->methods);

		parent::init();
	}

	/**
	 * Checks the current request's type to see if it is allowed
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if the request type is invalid
	 */
	protected function preFilter($filterChain)
	{
		$requestType = Yii::app()->request->requestType;

		if (in_array($requestType, $this->methods))
			return true;

		throw new CHttpException(400, 'Invalid request type');
	}

}