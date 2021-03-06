<?php

/**
 * Class Test_WPML_Qazana_Adjust_Global_Widget_ID
 */
class Test_WPML_Qazana_Adjust_Global_Widget_ID extends OTGS_TestCase {

	public function tearDown() {
		unset( $_GET );
		parent::tearDown();
	}

	/**
	 * @test
	 * @group wpmlcore-5793
	 */
	public function it_adds_hooks_on_admin() {
		\WP_Mock::userFunction( 'is_admin' , array(
			'return' => true,
		));

		$subject = new WPML_Qazana_Adjust_Global_Widget_ID(
			\Mockery::mock( 'IWPML_Page_Builders_Data_Settings' ),
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'SitePress' )
		);

		$this->expectActionAdded( 'qazana/editor/before_enqueue_scripts', array(
			$subject,
			'adjust_ids'
		), 10 );
		$this->expectActionAdded( 'qazana/editor/after_enqueue_scripts', array(
			$subject,
			'restore_current_language'
		), 10 );

		$this->expectFilterAdded( 'wpml_should_use_display_as_translated_snippet', array(
			$subject, 'should_use_display_as_translated_snippet'
		), PHP_INT_MAX, 2 );

		$subject->add_hooks();
	}

	/**
	 * @test
	 * @group wpmlcore-5793
	 */
	public function it_adds_hooks_on_frontend() {
		\WP_Mock::userFunction( 'is_admin' , array(
			'return' => false,
		));

		$subject = new WPML_Qazana_Adjust_Global_Widget_ID(
			\Mockery::mock( 'IWPML_Page_Builders_Data_Settings' ),
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'SitePress' )
		);

		$this->expectActionAdded( 'qazana/editor/before_enqueue_scripts', array(
			$subject,
			'adjust_ids'
		), 10 );
		$this->expectActionAdded( 'qazana/editor/after_enqueue_scripts', array(
			$subject,
			'restore_current_language'
		), 10 );

		$this->expectFilterAdded( 'wpml_should_use_display_as_translated_snippet', array(
			$subject, 'should_use_display_as_translated_snippet'
		), PHP_INT_MAX, 2, 0 );

		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function test_adjust_ids() {

		$post_id       = 12;
		$post_language = 'fr';
		\WP_Mock::passthruFunction( 'absint' );

		$global_id            = '20';
		$global_id_translated = '21';

		$qazana_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id,
				'elements'   => array(),
			)
		);

		$converted_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id_translated,
				'elements'   => array(),
			)
		);

		$settings = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$settings->shouldReceive( 'get_meta_field' )->andReturn( '_qazana_data' );
		$settings->shouldReceive( 'convert_data_to_array' )->with( 'post meta' )->andReturn( $qazana_data );
		$settings->shouldReceive( 'prepare_data_for_saving' )->with( $converted_data )->andReturn( $converted_data );

		$element = \Mockery::mock( 'WPML_Post_Element' );
		$element->shouldReceive( 'get_language_code' )->andReturn( $post_language );

		$global_element_translated = \Mockery::mock( 'WPML_Post_Element' );
		$global_element_translated->shouldReceive( 'get_element_id' )->andReturn( $global_id_translated );

		$global_element = \Mockery::mock( 'WPML_Post_Element' );
		$global_element->shouldReceive( 'get_language_code' )->andReturn( 'en' );
		$global_element->shouldReceive( 'get_translation' )->with( $post_language )->andReturn( $global_element_translated );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )->with( $post_id )->andReturn( $element );
		$element_factory->shouldReceive( 'create_post' )->with( $global_id )->andReturn( $global_element );

		\WP_Mock::userFunction( 'get_post_meta', array(
			'args'   => array( $post_id, '_qazana_data' ),
			'return' => 'post meta'
		) );

		$sitepress = \Mockery::mock( 'SitePress' );
		$sitepress->shouldReceive( 'get_current_language' )->andReturn( 'en' );
		$sitepress->shouldReceive( 'switch_lang' )->once()->with( 'fr' );

		$subject = new WPML_Qazana_Adjust_Global_Widget_ID(
			$settings,
			$element_factory,
			$sitepress
		);

		$_REQUEST['post'] = $post_id;

		$this->expect_post_is_updated_correctly( $post_id, $converted_data );

		$subject->adjust_ids();

		$sitepress->shouldReceive( 'switch_lang' )->once()->with( 'en' );
		$subject->restore_current_language();

		unset( $_REQUEST['post'] );
	}

	/**
	 * @test
	 */
	public function it_does_not_adjust_ids_when_custom_field_is_empty() {

		$post_id       = 12;
		$post_language = 'fr';
		\WP_Mock::passthruFunction( 'absint' );

		$global_id            = '20';
		$global_id_translated = '21';

		$qazana_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id,
				'elements'   => array(),
			)
		);

		$converted_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id_translated,
				'elements'   => array(),
			)
		);

		$settings = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$settings->shouldReceive( 'get_meta_field' )->andReturn( '_qazana_data' );
		$settings->shouldReceive( 'convert_data_to_array' )->with( 'post meta' )->andReturn( $qazana_data );
		$settings->shouldReceive( 'prepare_data_for_saving' )->with( $converted_data )->andReturn( $converted_data );

		$element = \Mockery::mock( 'WPML_Post_Element' );
		$element->shouldReceive( 'get_language_code' )->andReturn( $post_language );

		$global_element_translated = \Mockery::mock( 'WPML_Post_Element' );
		$global_element_translated->shouldReceive( 'get_element_id' )->andReturn( $global_id_translated );

		$global_element = \Mockery::mock( 'WPML_Post_Element' );
		$global_element->shouldReceive( 'get_language_code' )->andReturn( 'en' );
		$global_element->shouldReceive( 'get_translation' )->with( $post_language )->andReturn( $global_element_translated );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )->with( $post_id )->andReturn( $element );
		$element_factory->shouldReceive( 'create_post' )->with( $global_id )->andReturn( $global_element );

		\WP_Mock::userFunction( 'get_post_meta', array(
			'args'   => array( $post_id, '_qazana_data' ),
			'return' => array(),
		) );

		$sitepress = \Mockery::mock( 'SitePress' );
		$sitepress->shouldReceive( 'get_current_language' )->andReturn( 'en' );
		$sitepress->shouldReceive( 'switch_lang' )->once()->with( 'fr' );

		$subject = new WPML_Qazana_Adjust_Global_Widget_ID(
			$settings,
			$element_factory,
			$sitepress
		);

		$_REQUEST['post'] = $post_id;

		\WP_Mock::userFunction( 'update_post_meta', array(
			'times' => 0,
		) );

		$subject->adjust_ids();

		unset( $_REQUEST['post'] );
	}

	private function expect_post_is_updated_correctly( $post_id, $converted_data ) {
		\WP_Mock::userFunction( 'get_post', array( 'return' => array() ) );
		\WP_Mock::userFunction( 'current_time', array( 'return' => 'time' ) );

		\WP_Mock::userFunction( 'update_post_meta', array(
			'times' => 1,
			'args'  => array( $post_id, '_qazana_data', $converted_data )
		) );
		\WP_Mock::userFunction( 'wp_update_post', array(
			'times' => 1,
			'args'  => array( array( 'post_date' => 'time', 'post_date_gmt' => '' ) )
		) );
	}

	/**
	 * @test
	 * @dataProvider dp_not_use_display_as_translated_snippet
	 * @group wpmlcore-5793
	 *
	 * @param $_get
	 * @param $post_types
	 */
	public function it_should_not_alter_use_display_as_translated_snippet( $_get, $post_types ) {
		$_GET = $_get;

		$settings        = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$sitepress       = \Mockery::mock( 'SitePress' );

		$subject = new WPML_Qazana_Adjust_Global_Widget_ID( $settings, $element_factory, $sitepress );

		$this->assertFalse( $subject->should_use_display_as_translated_snippet( false, $post_types ) );
		$this->assertTrue( $subject->should_use_display_as_translated_snippet( true, $post_types ) );
	}

	public function dp_not_use_display_as_translated_snippet() {
		return array(
			array( array(), array( 'qazana_library' => array() ) ),
			array( array( 'action' => 'something' ), array( 'qazana_library' => array() ) ),
			array( array( 'action' => 'qazana' ), array( 'something' => array() ) ),
		);
	}

	/**
	 * @test
	 * @group wpmlcore-5793
	 */
	public function it_should_force_to_use_display_as_translated_snippet() {
		$_GET = array( 'action' => 'qazana' );
		$post_types = array(
			'something'         => array(),
			'qazana_library'	=> array(),
		);

		$settings        = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$sitepress       = \Mockery::mock( 'SitePress' );

		$subject = new WPML_Qazana_Adjust_Global_Widget_ID( $settings, $element_factory, $sitepress );

		$this->assertTrue( $subject->should_use_display_as_translated_snippet( false, $post_types ) );
	}
}
