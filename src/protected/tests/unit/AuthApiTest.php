<?php
class AuthApiTest extends ApiTest
{
	public function testLogin()
	{
		$result = $this->post('/login', json_encode((object) array('user' => TEST_USER, 'pass' => TEST_PASSWORD)));
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 201);
		$this->assertObjectHasAttribute('token', $model);
		$this->assertEmpty($result['location']);

		$result = $this->post('/login', json_encode((object) array('user' => 'random', 'pass' => 'password')));
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 403);
	}
}
