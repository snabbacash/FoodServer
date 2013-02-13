<?php
class UserApiTest extends ApiTest
{
	public function testIndex()
	{
		$result = $this->get('/user');
		$this->assertEquals($result['code'], 401);

		$result = $this->get('/user', null, $this->auth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertEquals($model->username, TEST_USER);
		$this->assertInternalType('string', $model->name);
		$this->assertGreaterThanOrEqual($model->balance, 0);
		$this->assertEquals($model->role, 'student');
		$this->assertEmpty($result['location']);

		$result = $this->get('/user', null, $this->auth);
	}

	public function testView()
	{
		$result = $this->get('/user/'.TEST_USER);
		$this->assertEquals($result['code'], 401);

		// @TODO this will require a specific role in the future
		$result = $this->get('/user/'.TEST_USER, null, $this->auth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertEquals($model->username, TEST_USER);
		$this->assertInternalType('string', $model->name);
		$this->assertGreaterThanOrEqual($model->balance, 0);
		$this->assertEquals($model->role, 'student');
		$this->assertEmpty($result['location']);

		// $result = $this->get('/user', null, $this->auth);
	}

	public function testUpdate()
	{
		// $result = $this->put('/user/scholdso', array());
		// $this->assertEquals($result['code'], 401);

		// $result = $this->put('/user/scholdso', array('user' => 'a'), $this->auth);
		// $this->assertEquals($result['code'], 403);

		// $result = $this->put('/user/scholdso', array('balance' => 3), $this->auth);
		// $model = json_decode($result['body']);
		// $this->assertEquals($result['code'], 200);
		// var_dump($model);
	}
}
