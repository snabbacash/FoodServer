<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	
	/**
	 * @var mixed decoded JSON data from POST requests
	 */
	protected $_decodedJsonData;
	
	/**
	 * Initializes the controller
	 */
	public function init()
	{
		// We will always be outputting JSON
		header("Content-type: application/json");
		
		parent::init();
	}
	
	/**
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array(
			'decodeJsonPayload',
		);
	}
	
	/**
	 * Decodes the "data" parameter from POST requests and stoes it in a member 
	 * variable so that we don't have to do it in every POST action.
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if we have a POST request without a "data" 
	 * parameter or if the JSON is malformed
	 */
	public function filterDecodeJsonPayload($filterChain)
	{
		if (Yii::app()->request->isPostRequest)
		{
			if (!isset($_POST['data']))
				throw new CHttpException(400, 'Invalid request');

			$data = CJSON::decode($_POST['data'], false);
			if ($data === null)
				throw new CHttpException(400, 'Malformed JSON data');

			$this->_decodedJsonData = $data;
		}

		// Continue execution
		$filterChain->run();
	}

}