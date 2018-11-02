<?php

/**
 * Class Test_WPML_Qazana_DB_Factory
 *
 * @group qazana-third-party
 * @group wpmlst-1535
 * @group qazana
 */
class Test_WPML_Qazana_DB_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_qazana_db() {
		$subject = new WPML_Qazana_DB_Factory();
		$this->assertInstanceOf( 'WPML_Qazana_DB', $subject->create() );
	}
}
