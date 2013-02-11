<?php
class MenuApiTest extends ApiTest
{
	public function testWeek()
	{
		$result = $this->get('/menu/12');
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 200);
		$this->assertEquals($model->week, 12);
		$this->assertEmpty($result['location']);

		$result = $this->get('/menu/53');
		$model = json_decode($result['body']);
		$this->assertEquals($result['code'], 404);
	}

	public function testList()
	{
		$result = $this->get('/menu/2012-01-01');
		$model = json_decode($result['body']);
		$this->assertEquals($model->date, '2012-01-01');

		$result = $this->get('/menu/today');
		$model = json_decode($result['body']);
		$this->assertEquals($model->date, date('Y-m-d'));
	}
}
