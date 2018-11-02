<?php

class WPML_Qazana_DB_Factory {

	/**
	 * @return null|WPML_Qazana_DB
	 */
	public function create() {
		$wpml_qazana_db = null;

		if ( version_compare( phpversion(), '5.3.0', '>=' ) && class_exists( '\Qazana\DB' ) ) {
			// @codingStandardsIgnoreLine
			$qazana_db = new \Qazana\DB();

			if ( method_exists( $qazana_db, 'save_plain_text' ) ) {
				$wpml_qazana_db = new WPML_Qazana_DB( $qazana_db );
			}
		}

		return $wpml_qazana_db;
	}
}
