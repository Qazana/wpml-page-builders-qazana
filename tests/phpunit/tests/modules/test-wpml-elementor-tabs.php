<?php

/**
 * Class Test_WPML_Qazana_Tabs
 *
 * @group page-builders
 * @group qazana
 */
class Test_WPML_Qazana_Tabs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'tab_title', 'tab_content' );
		$subject = new WPML_Qazana_Tabs();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Qazana_Tabs();
		$this->assertEquals( 'tabs', $subject->get_items_field() );
	}
}