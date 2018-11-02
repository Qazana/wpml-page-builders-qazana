<?php

/**
 * Class WPML_Qazana_Integration_Factory
 */
class WPML_Qazana_Integration_Factory {

	/**
	 * @return WPML_Page_Builders_Integration
	 */
	public function create() {
		$action_filter_loader = new WPML_Action_Filter_Loader();
		$action_filter_loader->load(
			array(
				'WPML_Qazana_Translate_IDs_Factory',
				'WPML_Qazana_URLs_Factory',
				'WPML_Qazana_Media_Translation_Factory',
				'WPML_Qazana_Adjust_Global_Widget_ID_Factory',
			)
		);

		$nodes                = new WPML_Qazana_Translatable_Nodes();
		$qazana_db_factory = new WPML_Qazana_DB_Factory();
		$data_settings        = new WPML_Qazana_Data_Settings( $qazana_db_factory->create() );

		$string_registration_factory = new WPML_String_Registration_Factory( $data_settings->get_pb_name() );
		$string_registration         = $string_registration_factory->create();

		$register_strings   = new WPML_Qazana_Register_Strings( $nodes, $data_settings, $string_registration );
		$update_translation = new WPML_Qazana_Update_Translation( $nodes, $data_settings );

		return new WPML_Page_Builders_Integration( $register_strings, $update_translation, $data_settings );
	}
}