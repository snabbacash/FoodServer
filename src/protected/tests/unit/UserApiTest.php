<?php
class UserApiTest extends ApiTest
{
	private $validAuth = array(
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		CURLOPT_USERPWD => 'hur:dur',
	);

	public function testView()
	{
		$result = $this->get('/accounts/scholdso');
		$this->assertEquals($result['code'], 401);

		$result = $this->get('/accounts/scholdso', null, $this->validAuth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertEquals($model->user, 'scholdso');
		$this->assertEmpty($result['location']);
	}

	public function testUpdate()
	{
		$result = $this->put('/accounts/scholdso', array());
		$this->assertEquals($result['code'], 401);

		$result = $this->put('/accounts/scholdso', array('user' => 'a'), $this->validAuth);
		$this->assertEquals($result['code'], 403);

		$result = $this->put('/accounts/scholdso', array('balance' => 3), $this->validAuth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
	}
}
