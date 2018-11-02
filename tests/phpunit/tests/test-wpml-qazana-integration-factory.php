<?php

/**
 * Class Test_WPML_Qazana_Integration_Factory
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @group page-builders
 * @group qazana
 */
class Test_WPML_Qazana_Integration_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates() {
		$qazana_factory = new WPML_Qazana_Integration_Factory();

		$action_filter_loader = \Mockery::mock( 'overload:WPML_Action_Filter_Loader' );
		$action_filter_loader->shouldReceive( 'load' )
		                     ->once()
		                     ->with( array(
		                     	'WPML_Qazana_Translate_IDs_Factory',
		                     	'WPML_Qazana_URLs_Factory',
			                     'WPML_Qazana_Media_Translation_Factory',
			                     'WPML_Qazana_Adjust_Global_Widget_ID_Factory',
		                     ) );

		$string_registration = \Mockery::mock( 'overload:WPML_PB_String_Registration' );

		$string_registration_factory = \Mockery::mock( 'overload:WPML_String_Registration_Factory' );
		$string_registration_factory->shouldReceive( 'create' )
		                            ->andReturn( $string_registration );

		$this->assertInstanceOf( 'WPML_Page_Builders_Integration', $qazana_factory->create() );
	}
}