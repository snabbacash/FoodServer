<?php
class OrderApiTest extends ApiTest
{
	private $validAuth = array(
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		CURLOPT_USERPWD => 'hur:dur',
	);

	public function testList()
	{
		$result = $this->get('/orders');
		$this->assertEquals($result['code'], 401);

		$result = $this->get('/orders', null, $this->validAuth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertTrue(is_array($model));
		$this->assertInstanceOf('stdClass', $model[0]);
		$this->assertEmpty($result['location']);
	}

	public function testView()
	{
		$result = $this->get('/orders/12');
		$this->assertEquals($result['code'], 401);

		$result = $this->get('/orders/12', null, $this->validAuth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertEquals($model->id, 12);
		$this->assertEmpty($result['location']);
	}

	public function testCreate()
	{
		$result = $this->post('/orders', array());
		$this->assertEquals($result['code'], 401);

		$result = $this->post('/orders', array(), $this->validAuth);
		$this->assertEquals($result['code'], 400);

		$result = $this->post('/orders', array('items' => 'a'), $this->validAuth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 201);
		$this->assertTrue(is_int($model->id));
		$this->assertEquals(trim($result['location']), '/orders/' . $model->id);
	}

	public function testUpdate()
	{
		$result = $this->put('/orders/12', array());
		$this->assertEquals($result['code'], 401);

		$result = $this->put('/orders/12', array('id' => 'asdf'), $this->validAuth);
		$this->assertEquals($result['code'], 403);

		$result = $this->put('/orders/12', array('items' => 'a'), $this->validAuth);
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertEquals($model->id, 12);
	}
}
