<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-10 at 17:21:17.
 */
class ImporterFiletypeXMLTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ImporterFiletypeXML
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new ImporterFiletypeXML;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() { }

	/**
	 * Test: empty string
	 */
	public function testEmptyString() {
		$this->object->parseString('');

		$this->assertTrue( $this->object->failed() );
		$this->assertEmpty( $this->object->objects() );
		$this->assertNotEmpty( $this->object->getErrors() );
	}

	/**
	 * Test: incorrect xml-file 
	 */
	public function test_incorrectString() {
		$this->object->parseString('<any><xml><file></file></xml></any>');

		$this->assertTrue( $this->object->failed() );
		$this->assertEmpty( $this->object->objects() );
		$this->assertNotEmpty( $this->object->getErrors() );
	}

	/**
	 * Test: Polar file
	 * Filename: "Polar.xml" 
	 */
	public function test_PolarFile() {
		$this->object->parseFile('../tests/testfiles/xml/Polar.xml');

		$this->assertFalse( $this->object->failed() );
		$this->assertFalse( $this->object->hasMultipleTrainings() );

		$this->assertEquals( mktime(11, 33, 9, 3, 24, 2013), $this->object->object()->getTimestamp() );
		$this->assertEquals( 6.6, $this->object->object()->getDistance() );
		$this->assertEquals( 725, $this->object->object()->getCalories() );
		$this->assertEquals( 48*60 + 49, $this->object->object()->getTimeInSeconds() );
		$this->assertEquals( 156, $this->object->object()->getPulseAvg() );
		$this->assertEquals( 179, $this->object->object()->getPulseMax() );

		$this->assertTrue( $this->object->object()->hasArrayHeartrate() );
	}

	/**
	 * Test: Polar file with multiple trainings
	 * Filename: "Multiple-Polar.xml
	 */
	public function test_PolarFile_Multiple() {
		$this->object->parseFile('../tests/testfiles/xml/Multiple-Polar.xml');

		$this->assertFalse( $this->object->failed() );
		$this->assertTrue( $this->object->hasMultipleTrainings() );
		$this->assertEquals( 6, $this->object->numberOfTrainings() );

		$this->assertEquals( mktime(7, 9, 34, 5, 11, 2011), $this->object->object(0)->getTimestamp() );
		$this->assertEquals( 17.1, $this->object->object(0)->getDistance() );

		$this->assertEquals( mktime(17, 31, 19, 5, 11, 2011), $this->object->object(1)->getTimestamp() );
		$this->assertEquals( 16.7, $this->object->object(1)->getDistance() );

		$this->assertEquals( mktime(7, 14, 49, 5, 12, 2011), $this->object->object(2)->getTimestamp() );
		$this->assertEquals( 17.0, $this->object->object(2)->getDistance() );

		$this->assertEquals( mktime(17, 35, 32, 5, 12, 2011), $this->object->object(3)->getTimestamp() );
		$this->assertEquals( 16.5, $this->object->object(3)->getDistance() );

		$this->assertEquals( mktime(7, 12, 15, 5, 13, 2011), $this->object->object(4)->getTimestamp() );
		$this->assertEquals( 17.0, $this->object->object(4)->getDistance() );

		$this->assertEquals( mktime(17, 5, 28, 5, 13, 2011), $this->object->object(5)->getTimestamp() );
		$this->assertEquals( 16.6, $this->object->object(5)->getDistance() );
	}

	/**
	 * Test: RunningAHEAD log
	 * Filename: "RunningAHEAD-Minimal-example.xml" 
	 */
	public function test_RunningAHEADFile() {
		$this->object->parseFile('../tests/testfiles/xml/RunningAHEAD-Minimal-example.xml');

		$this->assertFalse( $this->object->failed() );
		$this->assertTrue( $this->object->hasMultipleTrainings() );
		$this->assertEquals( 3, $this->object->numberOfTrainings() );

		// Event 1
		$this->assertEquals( CONF_RUNNINGSPORT, $this->object->object(0)->get('sportid') );
		$this->assertEquals( CONF_WK_TYPID, $this->object->object(0)->get('typeid') );
		$this->assertEquals( 193, $this->object->object(0)->getPulseAvg() );
		$this->assertEquals( 210, $this->object->object(0)->getPulseMax() );
		$this->assertEquals( 5.0, $this->object->object(0)->getDistance() );
		$this->assertEquals( 1157, $this->object->object(0)->getTimeInSeconds() );
		$this->assertEquals( "Citylauf Telgte", $this->object->object(0)->getRoute() );
		$this->assertEquals( 17, $this->object->object(0)->get('temperature') );
		$this->assertEquals( "Super organisiert, gute Strecke ...", $this->object->object(0)->getNotes() );

		// Event 2
		$this->assertNotEquals( CONF_RUNNINGSPORT, $this->object->object(1)->get('sportid') );
		$this->assertNotEquals( CONF_WK_TYPID, $this->object->object(1)->get('typeid') );
		$this->assertEquals( 1.0, $this->object->object(1)->getDistance() );
		$this->assertEquals( 2700, $this->object->object(1)->getTimeInSeconds() );

		// Event 3
		$this->assertEquals( CONF_RUNNINGSPORT, $this->object->object(2)->get('sportid') );
		$this->assertNotEquals( CONF_WK_TYPID, $this->object->object(2)->get('typeid') );
		$this->assertEquals( 182, $this->object->object(2)->getPulseAvg() );
		$this->assertEquals( 189, $this->object->object(2)->getPulseMax() );
		$this->assertEquals( 4.0, $this->object->object(2)->getDistance() );
		$this->assertEquals( 1000, $this->object->object(2)->getTimeInSeconds() );
		$this->assertEquals( "Bahn Sentruper Hoehe", $this->object->object(2)->getRoute() );
		$this->assertEquals( "4 x 1 km, 400 m Trab", $this->object->object(2)->getComment() );
		$this->assertFalse( $this->object->object(2)->Weather()->isUnknown() );
		$this->assertEquals( 15, $this->object->object(2)->get('temperature') );

		$this->assertEquals(
			"1.00|4:10-R0.40|3:00-1.00|4:10-R0.40|3:00-1.00|4:10-R0.40|3:00-1.00|4:10-R1.60|8:00",
			$this->object->object(2)->Splits()->asString()
		);
	}

	/**
	 * Test: Suunto file
	 * Filename: "Suunto-Ambit-reduced.xml" 
	 */
	public function test_SuuntoFile() {
		$this->object->parseFile('../tests/testfiles/xml/Suunto-Ambit-reduced.xml');

		$this->assertFalse( $this->object->failed() );
		$this->assertFalse( $this->object->hasMultipleTrainings() );

		$this->assertEquals( mktime(15, 28, 0, 4, 28, 2013), $this->object->object()->getTimestamp() );
		$this->assertEquals( 0.264, $this->object->object()->getDistance() );
		$this->assertEquals( 107, $this->object->object()->getTimeInSeconds() );
		$this->assertEquals( 133, $this->object->object()->getPulseAvg() );
		$this->assertEquals( 143, $this->object->object()->getPulseMax() );
		$this->assertEquals( 26, $this->object->object()->get('temperature') );

		$this->assertTrue( $this->object->object()->hasArrayHeartrate() );
		$this->assertTrue( $this->object->object()->hasArrayAltitude() );
		$this->assertTrue( $this->object->object()->hasArrayDistance() );
		$this->assertTrue( $this->object->object()->hasArrayLatitude() );
		$this->assertTrue( $this->object->object()->hasArrayLongitude() );
		$this->assertTrue( $this->object->object()->hasArrayPace() );
		$this->assertTrue( $this->object->object()->hasArrayTemperature() );
		$this->assertTrue( $this->object->object()->hasArrayTime() );
	}

}