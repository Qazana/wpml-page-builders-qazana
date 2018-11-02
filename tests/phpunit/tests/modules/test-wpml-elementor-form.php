<?php

/**
 * Class Test_WPML_Qazana_Form
 *
 * @group page-builders
 * @group qazana
 */
class Test_WPML_Qazana_Form extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'field_label', 'placeholder', 'field_html', 'acceptance_text', 'field_options' );
		$subject = new WPML_Qazana_Form();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Qazana_Form();
		$this->assertEquals( 'form_fields', $subject->get_items_field() );
	}
}