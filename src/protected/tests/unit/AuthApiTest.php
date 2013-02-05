<?php
class AuthApiTest extends ApiTest
{
	public function testLogin()
	{
		$result = $this->post('/login', array('username' => ''));
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 201);
		$this->assertObjectHasAttribute('token', $model);
		$this->assertEmpty($result['location']);

		$result = $this->post('/login', array());
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 403);
	}
}
