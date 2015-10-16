<?php

namespace Runalyze\Model\Sport;

/**
 * Generated by hand
 */
class ObjectTest extends \PHPUnit_Framework_TestCase {

	public function testSimpleObject() {
		$Sport = new Object(array(
			Object::NAME => 'Sport name',
			Object::SHORT => 0,
			Object::CALORIES_PER_HOUR => 700,
			Object::HR_AVG => 140,
			Object::HAS_DISTANCES => 1,
			Object::PACE_UNIT => 'foo',
			Object::HAS_TYPES => 1,
			Object::HAS_POWER => 0,
			Object::IS_OUTSIDE => 1
		));

		$this->assertEquals('Sport name', $Sport->name());
		$this->assertEquals(700, $Sport->caloriesPerHour());
		$this->assertEquals(140, $Sport->avgHR());
		$this->assertEquals('foo', $Sport->paceUnitEnum());
		$this->assertTrue($Sport->hasDistances());
		$this->assertTrue($Sport->hasTypes());
		$this->assertTrue($Sport->isOutside());

		$this->assertFalse($Sport->usesShortDisplay());
		$this->assertFalse($Sport->hasPower());
	}

}
