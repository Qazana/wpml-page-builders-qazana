<?php

class Test_WPML_Qazana_URLs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {

		$subject = new WPML_Qazana_URLs(
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'IWPML_URL_Converter_Strategy' ),
			\Mockery::mock( 'IWPML_Current_Language' )
		);

		$this->expectFilterAdded( 'qazana/document/urls/edit', array(
			$subject,
			'adjust_edit_with_qazana_url'
		), 10, 2 );

		$subject->add_hooks();

	}

	/**
	 * @test
	 */
	public function it_adjusts_url_for_domain() {

		$original_url   = 'http://my-site.com/wp-admin/post.php?post=6&action=qazana';
		$translated_url = 'http://fr.my-site.com/wp-admin/post.php?post=6&action=qazana';

		$post_language = 'fr';

		$post = (object) array( 'ID' => 123 );

		$post_element = \Mockery::mock( 'WPML_Post_Element' );
		$post_element->shouldReceive( 'get_language_code' )->andReturn( $post_language );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )
		                ->with( $post->ID )
		                ->andReturn( $post_element );

		$language_converter = \Mockery::mock( 'IWPML_URL_Converter_Strategy' );
		$language_converter->shouldReceive( 'convert_admin_url_string' )
		                   ->with( $original_url, $post_language )
		                   ->andReturn( $translated_url );

		$current_language = \Mockery::mock( 'IWPML_Current_Language' );

		$subject = new WPML_Qazana_URLs(
			$element_factory,
			$language_converter,
			$current_language
		);

		$qazana_document = \Mockery::mock( 'Qazana_Document' ); // Note: this is not the real class name
		$qazana_document->shouldReceive( 'get_main_post' )->andReturn( $post );

		$this->assertEquals( $translated_url, $subject->adjust_edit_with_qazana_url( $original_url, $qazana_document ) );
	}

	/**
	 * @test
	 */
	public function it_adjusts_url_for_domain_using_current_language_if_element_has_no_language() {
		$original_url   = 'http://my-site.com/wp-admin/post.php?post=6&action=qazana';
		$translated_url = 'http://fr.my-site.com/wp-admin/post.php?post=6&action=qazana';

		$site_language = 'fr';

		$post = (object) array( 'ID' => 123 );

		$post_element = \Mockery::mock( 'WPML_Post_Element' );
		$post_element->shouldReceive( 'get_language_code' )->andReturn( '' );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )
		                ->with( $post->ID )
		                ->andReturn( $post_element );

		$language_converter = \Mockery::mock( 'IWPML_URL_Converter_Strategy' );
		$language_converter->shouldReceive( 'convert_admin_url_string' )
		                   ->with( $original_url, $site_language )
		                   ->andReturn( $translated_url );

		$current_language = \Mockery::mock( 'IWPML_Current_Language' );
		$current_language->shouldReceive( 'get_current_language' )->andReturn( $site_language );

		$subject = new WPML_Qazana_URLs(
			$element_factory,
			$language_converter,
			$current_language
		);

		$qazana_document = \Mockery::mock( 'Qazana_Document' ); // Note: this is not the real class name
		$qazana_document->shouldReceive( 'get_main_post' )->andReturn( $post );

		$this->assertEquals( $translated_url, $subject->adjust_edit_with_qazana_url( $original_url, $qazana_document ) );
	}

}