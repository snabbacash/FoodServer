<?php
class MenuApiTest extends ApiTest
{
	public function testWeek()
	{
		$result = $this->get('/menu/' . date('W'), null, $this->auth);
		$model = json_decode($result['body']);

		$this->assertNotEmpty($model);
		// @TODO structure is wrong atm.
	}

	public function testView()
	{
		$result = $this->get('/menu/' . date('Y-m-d'), null, $this->auth);
		$model = json_decode($result['body']);
		$this->assertEquals($model->date, date('Y-m-d'));

		$result = $this->get('/menu/today', null, $this->auth);
		$model = json_decode($result['body']);

		$this->assertEquals($model->date, date('Y-m-d'));
		$this->assertInternalType('array', $model->menu);
		$this->assertInternalType('array', $model->menu);
		$meal = $model->menu[0];
		$this->assertInternalType('numeric', $meal->id);
		$this->assertInternalType('numeric', $meal->price->student);
		$this->assertInternalType('numeric', $meal->price->staff);
		$this->assertInternalType('numeric', $meal->price->other);
		$this->assertNotEmpty($meal->parts);
		$part = $meal->parts[0];
		$this->assertInternalType('numeric', $part->id);
		$this->assertInternalType('numeric', $part->food);
		$this->assertInternalType('string', $part->name);
		$this->assertInternalType('string', $part->diets);
	}
}
