<?php
// phpcs:ignoreFile

namespace AutomateWoo;

/**
 * @class Tools
 * @since 2.4.5
 */
class Tools {

	/** @var Tool_Abstract[] $tools */
	public static $tools = [];


	/**
	 * @return Tool_Abstract[]
	 */
	static function get_tools() {
		if ( empty( self::$tools ) ) {
			$tool_classes = [];

			$tool_classes[] = Options::optin_enabled() ? Tool_Optin_Importer::class : Tool_Optout_Importer::class;
			$tool_classes[] = Guest_Eraser::class;
			$tool_classes[] = Tool_Reset_Workflow_Records::class;

			$tool_classes = apply_filters( 'automatewoo/tools', $tool_classes );

			foreach ( $tool_classes as $tool_class ) {
				/** @var Tool_Abstract $class */
				$class = new $tool_class();
				self::$tools[$class->get_id()] = $class;
			}
		}

		return self::$tools;
	}


	/**
	 * @param $id
	 * @return Tool_Abstract|false
	 */
	static function get_tool( $id ) {
		$tools = self::get_tools();

		if ( isset( $tools[$id] ) ) {
			return $tools[$id];
		}

		return false;
	}


	/**
	 * @param array $tasks
	 * @return bool|\WP_Error
	 */
	static function init_background_process( $tasks ) {
		/** @var Background_Processes\Tools $process */
		$process = Background_Processes::get('tools');
		$process->data( $tasks )->start();
		return true;
	}

}
