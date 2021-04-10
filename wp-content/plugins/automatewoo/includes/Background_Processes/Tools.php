<?php
// phpcs:ignoreFile

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Clean;
use AutomateWoo\Tool_Background_Processed_Abstract;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for tools.
 *
 * @deprecated in 5.2.0 and replaced with the ToolTaskRunner job powered by ActionScheduler.
 * @see \AutomateWoo\Jobs\ToolTaskRunner
 *
 * @since 3.8
 */
class Tools extends Base {

	/** @var string  */
	public $action = 'tools';


	/**
	 * @param array $data
	 * @return mixed
	 */
	protected function task( $data ) {
		$tool = isset( $data['tool_id'] ) ? AW()->tools_service()->get_tool( Clean::string( $data['tool_id'] ) ) : false;

		if ( $tool ) {
			/** @var Tool_Background_Processed_Abstract $tool */
			$tool->handle_background_task( $data );
		}

		return false;
	}

}
