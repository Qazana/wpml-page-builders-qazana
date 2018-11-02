<?php

class WPML_Qazana_Adjust_Global_Widget_ID_Factory implements IWPML_Backend_Action_Loader, IWPML_Frontend_Action_Loader {

	public function create() {
		global $sitepress;

		$qazana_db_factory = new WPML_Qazana_DB_Factory();
		$data_settings        = new WPML_Qazana_Data_Settings( $qazana_db_factory->create() );

		return new WPML_Qazana_Adjust_Global_Widget_ID(
				$data_settings,
				new WPML_Translation_Element_Factory( $sitepress ),
				$sitepress
			);
	}
}