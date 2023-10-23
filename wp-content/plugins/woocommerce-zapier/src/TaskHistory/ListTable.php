<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\TaskHistory\Task\Task;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;
use OM4\WooCommerceZapier\Webhook\Resources;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use WP_List_Table;

defined( 'ABSPATH' ) || exit;

/**
 * History List Table, used for displaying history records in the WordPress
 * admin area.
 *
 * Used on the main WooCommerce Zapier screen, as well as in metaboxes when
 * editing one specific product/order/etc. We can't initiate this class early
 * on, because the `WP_List_Table` class not available early on. Therefore, this
 * class only started in the OM4\WooCommerceZapier\TaskHistory\UI class.
 *
 * @since 2.0.0
 */
class ListTable extends WP_List_Table {

	/**
	 * The list of items (records) to be shown in the List Table.
	 *
	 * @var Task[]
	 */
	public $items = array();

	/**
	 * Whether this table is in metabox mode.
	 *
	 * @var bool
	 */
	protected $metabox_mode = false;

	/**
	 * Resource type(s) (used when displaying in metabox mode).
	 *
	 * @var string[]
	 */
	protected $resource_types;

	/**
	 * Resource ID (used when displaying in metabox mode).
	 *
	 * @var int
	 */
	protected $resource_id;

	/**
	 * Number of items shown per page (used in pagination).
	 *
	 * @var int
	 */
	protected $items_per_page = 25;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Resources instance.
	 *
	 * @var Resources
	 */
	protected $webhook_resources;

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $check;

	/**
	 * ResourceManager instance.
	 *
	 * @var ResourceManager
	 */
	protected $resource_manager;

	/**
	 * Constructor.
	 *
	 * @param TaskDataStore   $data_store TaskDataStore instance.
	 * @param Resources       $webhook_resources Resources instance.
	 * @param FeatureChecker  $check FeatureChecker instance.
	 * @param ResourceManager $resource_manager ResourceManager instance.
	 */
	public function __construct(
		TaskDataStore $data_store,
		Resources $webhook_resources,
		FeatureChecker $check,
		ResourceManager $resource_manager
	) {
		$this->data_store        = $data_store;
		$this->webhook_resources = $webhook_resources;
		$this->check             = $check;
		$this->resource_manager  = $resource_manager;
		parent::__construct(
			array(
				'singular' => 'task-history',
				'plural'   => 'task-history',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Enable metabox mode for this list table.
	 *
	 * In metabox mode, the table shows task history records for one particular resource only,
	 * and it shows 10 records per page.
	 *
	 * @param string $resource_type Resource type (eg product, order, etc).
	 * @param int    $resource_id   Resource ID (eg product ID).
	 *
	 * @return void
	 */
	public function enable_metabox_mode( $resource_type, $resource_id ) {
		$this->metabox_mode   = true;
		$this->resource_types = array( $resource_type );
		$this->resource_id    = $resource_id;
		$this->items_per_page = 10;
	}

	/**
	 * Prepare table list items.
	 *
	 * @return void
	 */
	public function prepare_items() {
		$this->prepare_column_headers();

		$this->items = array();

		$args = array();

		if ( $this->metabox_mode ) {
			$args['resource_id']   = $this->resource_id;
			$args['resource_type'] = $this->resource_types;
		}

		// Pagination.
		$args['limit']  = $this->items_per_page;
		$args['offset'] = $this->items_per_page * ( $this->get_pagenum() - 1 );
		$total_items    = $this->data_store->get_tasks_count( $args );
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $this->items_per_page,
				'total_pages' => (int) \ceil( $total_items / $this->items_per_page ),
			)
		);
		$this->items = $this->data_store->get_tasks( $args );
	}

	/**
	 * Get column names/headings.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns['date_time'] = __( 'Date/Time', 'woocommerce-zapier' );
		$columns['message']   = __( 'Message', 'woocommerce-zapier' );
		return $columns;
	}

	/**
	 * Generates the table navigation above or below the table.
	 *
	 * Executed for both top and bottom navigation, on both the main Task History table and the Task History metabox.
	 *
	 * @param 'bottom'|'top' $which Required by WordPress.
	 *
	 * @return void
	 */
	protected function display_tablenav( $which ) {
		if ( $this->metabox_mode && 'top' === $which ) {
			// When in metabox mode, don't output the top pagination.
			return;
		}
		parent::display_tablenav( $which );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.7.0
	 *
	 * @param string $which Required by WordPress.
	 */
	protected function bulk_actions( $which = '' ) {
		if ( $this->metabox_mode ) {
			foreach ( $this->resource_types as $resource_type ) {
				if ( in_array( $resource_type, array( 'order', 'subscription' ), true ) ) {
					// Don't show bulk actions for orders or subscriptions.
					// Ensures that the bulk actions dropdown is not shown when in metabox mode for orders when HPOS is enabled.
					return;
				}
			}
		}
		// @phpstan-ignore-next-line Function signature mismatching in parent.
		parent::bulk_actions( $which );
	}

	/**
	 * Set _column_headers property for table list
	 *
	 * @return void
	 */
	protected function prepare_column_headers() {
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
		);
	}

	/**
	 * Date/Time column output.
	 *
	 * @param Task $task Task History Record.
	 *
	 * @return string
	 */
	public function column_date_time( $task ) {
		$date_time = $task->get_date_time();
		if ( ! $date_time ) {
			return '';
		}
		// Translators: Date/time column output for a Task. 1: Date Format, 2: Time Format.
		return esc_html( $date_time->date_i18n( sprintf( _x( '%1$s %2$s', 'Task date/time.', 'woocommerce-zapier' ), get_option( 'date_format' ), get_option( 'time_format' ) ) ) );
	}

	/**
	 * Message column output.
	 *
	 * @param  Task $task Task History Record.
	 *
	 * @return string
	 */
	public function column_message( $task ) {
		$resource = $this->resource_manager->get_resource( $task->get_resource_type() );
		if ( ! $resource ) {
			return '';
		}
		if ( $this->metabox_mode ) {
			return $task->get_message();
		}
		$resource_url = $resource->get_admin_url( $task->get_resource_id() );
		return wp_kses_post(
			\sprintf(
				// Translators: 1: Resource Edit URL. 2: Task history message.
				__( '<a href="%1$s">%2$s</a>', 'woocommerce-zapier' ),
				esc_attr( $resource_url ),
				$task->get_message()
			)
		);
	}

	/**
	 * Display/output the list table.
	 *
	 * @return void
	 */
	public function display() {
		// CSS that specifies column widths.
		?>
<style type="text/css">
.wp-list-table.task-history .column-date_time { width: 20%; }
.wp-list-table.task-history .column-message { width: 80%; }
</style>
		<?php
		parent::display();
	}

	/**
	 * Get the name of the specified webhook topic.
	 *
	 * @param string $topic_key Webhook Topic key.
	 *
	 * @return string
	 */
	protected function get_webhook_topic_name( $topic_key ) {
		$topics = $this->webhook_resources->get_topics();
		return isset( $topics[ $topic_key ] ) ? $topics[ $topic_key ] : '';
	}

	/**
	 * Message to be displayed when there are no items in this list table.
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No history records found.', 'woocommerce-zapier' );
	}
}
