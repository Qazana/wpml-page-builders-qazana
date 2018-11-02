<?php

/**
 * Class Test_WPML_Qazana_DB
 *
 * @group qazana-third-party
 * @group wpmlst-1535
 * @group qazana
 */
class Test_WPML_Qazana_DB extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_saves_plain_text() {
		$qazana_db = $this->getMockBuilder( '\Qazana\DB' )
		                     ->setMethods( array( 'save_plain_text' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$post_id = mt_rand( 1, 10 );

		$qazana_db->expects( $this->once() )
		             ->method( 'save_plain_text' )
		             ->with( $post_id );

		$subject = new WPML_Qazana_DB( $qazana_db );
		$subject->save_plain_text( $post_id );
	}
}
