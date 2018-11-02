<?php

/**
 * Class Test_WPML_Qazana_Slides
 *
 * @group page-builders
 * @group qazana
 */
class Test_WPML_Qazana_Slides extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'heading', 'description', 'button_text', 'link' => array( 'url' ) );
		$subject = new WPML_Qazana_Slides();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Qazana_Slides();
		$this->assertEquals( 'slides', $subject->get_items_field() );
	}
}