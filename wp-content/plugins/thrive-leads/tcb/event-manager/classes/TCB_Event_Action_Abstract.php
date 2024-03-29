<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 05.08.2014
 * Time: 14:37
 */

/**
 * base abstract class that will be extended by all available actions
 *
 * Class TCB_Event_Action_Abstract
 */
abstract class TCB_Event_Action_Abstract {
	/**
	 * @var array|mixed $data holds data that will be available in the view settings file
	 */
	protected $data;

	protected $key = '';

	/**
	 * holds the configuration values for this action
	 *
	 * @var array
	 */
	protected $config = [];

	protected $flag_available = null;

	/**
	 * instantiate a new Event Action class based on $class_name
	 *
	 * if a class does not exist, it tries to automatically include it from within the /actions folder
	 *
	 * if it's a third-party API - added action, the class MUST previously exist
	 *
	 * @param string $class_name
	 * @param mixed  $constructor_param optional argument to call the class constructor with
	 *
	 * @return TCB_Event_Action_Abstract
	 *
	 * @throws Exception
	 */
	public static final function actionFactory( $class_name, $constructor_param = null ) {
		if ( ! class_exists( $class_name ) && file_exists( __DIR__ . "/actions/$class_name.php" ) ) {
			require_once __DIR__ . "/actions/$class_name.php";
		}

		if ( ! class_exists( $class_name ) ) {
			throw new Exception( 'TCB Event Action factory: Could not find ' . $class_name );
		}

		return new $class_name( $constructor_param );
	}

	/**
	 * used only by TCB, it will render a view from /settings folder
	 *
	 * @param string $view view file to be rendered
	 * @param mixed  $data data to make available in the view
	 *
	 * @return string
	 */
	protected final function renderTCBSettings( $view, $data ) {
		if ( substr( $view, - 4 ) !== '.php' ) {
			$view .= '.php';
		}
		$file = dirname( dirname( __FILE__ ) ) . '/views/settings/' . $view;

		if ( ! file_exists( $file ) ) {
			exit( 'No settings found' );
		}

		if ( ! empty( $this->config ) ) {
			$data['config'] = $this->config;
		}

		$this->data = $data;
		ob_start();
		include $file;
		$content = ob_get_contents();
		ob_end_clean();

		return $content;

	}

	/**
	 * sets the saved configuration values for this Action
	 *
	 * @param $config
	 *
	 * @return TCB_Event_Action_Abstract
	 */
	public final function setConfig( $config ) {
		$this->config = $config;

		return $this;
	}

	/**
	 * magic getter for the view variables
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
	}

	/**
	 * makes all necessary changes to the content depending on the $data param
	 *
	 * this gets called each time this action is encountered in the DOM event configuration
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function applyContentFilter( $data ) {
		return '';
	}

	/**
	 * this will get displayed after the Action Name in the Actions list on the editor page
	 */
	public function getSummary() {
		return '';
	}

	/**
	 * abstract methods that must be implemented in each of the derived classes
	 */

	/**
	 * Should return the user-friendly name for this Action
	 *
	 * @return string
	 */
	public abstract function getName();

	/**
	 * Should return an actual string containing the JS function that's handling this action.
	 * The function will be called with 3 parameters:
	 *      -> event_trigger (e.g. click, dblclick etc)
	 *      -> action_code (the action that's being executed)
	 *      -> config (specific configuration for each specific action - the same configuration that has been setup in the settings section)
	 *
	 * Example (php): return 'function (trigger, action, config) { console.log(trigger, action, config); }';
	 *
	 * The function will be called in the context of the element
	 *
	 * The output MUST be a valid JS function definition.
	 *
	 * @return string the JS function definition (declaration + body)
	 */
	public abstract function getJsActionCallback();

	/**
	 * this will only be called ONCE if this action is encountered in the element's settings, and it should output all the necessary
	 * preparation javascript, such as helpers etc for this action
	 * It should use namespaces and WP naming conventions, we don't want to mess up the global namespace with these
	 *
	 * @return mixed
	 */
	public function outputGlobalJavascript() {

	}

	/**
	 * validate the current configuration for an action
	 */
	public function validateConfig() {
		return true;
	}

	/**
	 * called in the main loop when getting the main page / post content - used to append stuff such as fonts / icons that are (possibly) required in the action handler
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function mainPostCallback( $data ) {

	}

	/**
	 * should check if the current action is available to be displayed in the lists inside the event manager
	 *
	 * @return bool
	 */
	public function is_available() {
		return isset( $this->flag_available ) ? $this->flag_available : true;
	}

	/**
	 * Return the javascript constructor name for a view which will be instantiated for the editor settings of this action
	 *
	 * @return string
	 */
	public function get_editor_js_view() {
		return 'ActionDefault';
	}

	/**
	 * Getter action key
	 */
	public function get_key() {
		return $this->key;
	}

	public function set_key( $key ) {
		$this->key = $key;

		return $this;
	}

	/**
	 * Render the settings for this action
	 */
	public function render_editor_settings() {
		echo '';
	}

	public function get_options() {
		return [];
	}

	public function set_is_available( $flag ) {
		$this->flag_available = $flag;

		return $this;
	}
}
