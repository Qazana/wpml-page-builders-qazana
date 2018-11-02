<?php

class WPML_Qazana_DB {

	/**
	 * @var \Qazana\DB
	 */
	private $qazana_db;

	// @codingStandardsIgnoreLine
	public function __construct( \Qazana\DB $qazana_db ) {
		$this->qazana_db = $qazana_db;
	}

	/**
	 * @param int $post_id
	 */
	public function save_plain_text( $post_id ) {
		$this->qazana_db->save_plain_text( $post_id );
	}
}
