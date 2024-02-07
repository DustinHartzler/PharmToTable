<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Plugin;
use OM4\WooCommerceZapier\TaskHistory\Task\Task;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;
use OM4\WooCommerceZapier\Webhook\Resources;
use OM4\WooCommerceZapier\WooCommerceResource\Definition;
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
		} elseif (
			isset( $_GET['_wcznonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wcznonce'] ) ), 'zapier-task-history' )
		) {
			if ( isset( $_GET['status'] ) ) {
				switch ( sanitize_text_field( wp_unslash( $_GET['status'] ) ) ) {
					case 'success':
						// Successful tasks.
						$args['status'] = Task::STATUS_SUCCESS;
						break;
					case 'error':
						// Errored tasks.
						$args['status_not'] = Task::STATUS_SUCCESS;
						break;
					default:
						// All statuses (the default).
						break;
				}
			}

			if ( isset( $_GET['s'] ) ) {
				$args['search'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
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
		$columns['status']    = __( 'Status', 'woocommerce-zapier' );
		$columns['message']   = __( 'Message', 'woocommerce-zapier' );
		$columns['date_time'] = __( 'Date', 'woocommerce-zapier' );
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
	 * Displays extra controls between bulk actions and pagination.
	 *
	 * @since 2.10.0
	 *
	 * @param string $which Required by WordPress.
	 * @return void
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
			$status = ( isset( $_GET['status'] ) && isset( $_GET['_wcznonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wcznonce'] ) ), 'zapier-task-history' ) )
				? sanitize_text_field( wp_unslash( $_GET['status'] ) )
				: '';
			?>
			<div class="alignleft actions bulkactions">
				<select name="status">
					<option value="" <?php selected( '', $status ); ?>><?php esc_html_e( 'All statuses', 'woocommerce-zapier' ); ?></option>
					<option value="success" <?php selected( 'success', $status ); ?>><?php esc_html_e( 'OK', 'woocommerce-zapier' ); ?></option>
					<option value="error" <?php selected( 'error', $status ); ?>><?php esc_html_e( 'Error', 'woocommerce-zapier' ); ?></option>
				</select>
				<?php
				submit_button( __( 'Filter', 'woocommerce-zapier' ), '', 'filter_action', false, array( 'id' => 'zapier-task-history-submit' ) );
				?>
			</div>
			<?php
		}
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
	 * Status column output.
	 * Displays a tick or cross depending on whether the task succeeded or failed.
	 *
	 * @since 2.10.0
	 *
	 * @param Task $task Task History Record.
	 *
	 * @return string
	 */
	public function column_status( $task ) {
		switch ( $task->get_status() ) {
			case Task::STATUS_SUCCESS:
				return '<mark class="yes" title="'
						. esc_html( _x( 'OK', 'Successful task history status tooltip', 'woocommerce-zapier' ) )
						. '"><span class="dashicons dashicons-yes"></span> '
						. esc_html( _x( 'OK', 'Successful task history status', 'woocommerce-zapier' ) )
						. '</mark>';
			case '':
				// A task with an empty status. Unlikely, but possible if the database upgrade routine has issues.
				return '';
			default:
				// An error code.
				return '<mark class="error" title="'
						. esc_html( _x( 'Error', 'Errored task history status tooltip', 'woocommerce-zapier' ) )
						. '"><span class="dashicons dashicons-warning"></span> '
						. esc_html( _x( 'Error', 'Successful task history status', 'woocommerce-zapier' ) )
						. '</mark>';
		}
	}

	/**
	 * Info Action Link output.
	 *
	 * Displays a useful help tip for a Task History Task, containing useful structured information for support staff.
	 *
	 * Includes:
	 * - Triggers: Trigger Rule (Key), Webhook ID.
	 * - Actions: Action (Key).
	 *
	 * @since 2.10.0
	 *
	 * @param Task $task Task History Record.
	 *
	 * @return string
	 */
	protected function action_link_info( $task ) {
		if ( 'action' === $task->get_event_type() ) {
			return wc_help_tip(
				sprintf(
					// Translators: Action Key.
					__(
						'Action: %s',
						'woocommerce-zapier'
					),
					! empty( $task->get_event_topic() )
						? $task->get_event_topic()
						: __( 'unknown', 'woocommerce-zapier' )
				)
			);
		}
		// Trigger.
		return wc_help_tip(
			sprintf(
				// Translators: 1: Trigger Rule Key. 2. Webhook ID.
				__(
					'Trigger Rule: %1$s<br />Webhook ID: %2$d',
					'woocommerce-zapier'
				),
				! empty( $task->get_event_topic() )
					? $task->get_event_topic()
					: __( 'unknown', 'woocommerce-zapier' ),
				! empty( $task->get_webhook_id() )
					? $task->get_webhook_id()
					: __( 'unknown', 'woocommerce-zapier' )
			)
		);
	}

	/**
	 * Message column output.
	 *
	 * @param Task $task Task History Record.
	 *
	 * @return string
	 */
	public function column_message( $task ) {
		$resource = $this->resource_manager->get_resource( $task->get_resource_type() );
		if ( ! $resource ) {
			return '';
		}

		return wp_kses_post(
			\sprintf(
				// Translators: 1: Task history message. 2: Task history action link HTML.
				__( '%1$s<br />%2$s', 'woocommerce-zapier' ),
				$task->get_message(),
				parent::row_actions( $this->get_action_links( $task, $resource ) )
			)
		);
	}

	/**
	 * Get the action links for a Task History Task.
	 *
	 * @param Task       $task Task History Record.
	 * @param Definition $resource Resource Definition.
	 *
	 * @return array<string, string>
	 */
	protected function get_action_links( $task, $resource ) {
		$action_links = array();

		if ( Task::STATUS_SUCCESS !== $task->get_status() ) {
			// Errored Task.
			// Show Help action link.
			$action_links['help'] = sprintf(
				// Translators: 1: Help link URL.
				__( '<a href="%1$s" target="_blank">Get Help</a>', 'woocommerce-zapier' ),
				sprintf(
					'%1$serror-codes/?code=%2$s',
					Plugin::DOCUMENTATION_URL,
					str_replace( '-', '_', sanitize_title( $task->get_status() ) )
				)
			);
		}

		if ( ! $this->metabox_mode && $task->get_resource_id() > 0 ) {
			// Only show the "View Resource" action link on main List Table (and not in metabox mode),
			// and only if the task has a resource ID.
			$action_links['view'] = sprintf(
				sprintf(
				// Translators: 1: View link URL. 2: Resource Name.
					__( '<a href="%1$s">View %2$s</a>', 'woocommerce-zapier' ),
					$resource->get_admin_url( $task->get_resource_id() ),
					$resource->get_name()
				)
			);
		}
		if ( Task::STATUS_SUCCESS !== $task->get_status() ) {
			// Errored Task.
			// Show Error Code action link.
			$action_links['error_code'] = sprintf(
				// Translators: Error Code.
				__( 'Error Code: %s', 'woocommerce-zapier' ),
				$task->get_status()
			);
		}

		// Show Info action link.
		$action_links['info'] = $this->action_link_info( $task );
		return $action_links;
	}

	/**
	 * Display/output the list table.
	 *
	 * @return void
	 */
	public function display() {
		?>
		<style type="text/css">
			/* Column widths. */
			#woocommerce-zapier-task-history .wp-list-table.task-history .column-status {
				width: 8ch;
			}

			#woocommerce-zapier-task-history .wp-list-table.task-history .column-date_time {
				width: 20%;
				word-wrap: break-word;
			}

			#woocommerce-zapier-task-history .wp-list-table.task-history .column-message {
				width: calc( 100% - 20% - 8ch );
			}

			/* Error colouring (similar to the WooCommerce Status Report). */
			#woocommerce-zapier-task-history .wp-list-table.task-history tr.error a {
				text-decoration: none;
			}

			#woocommerce-zapier-task-history .wp-list-table.task-history tr.error td {
				vertical-align: top !important;
			}

			#woocommerce-zapier-task-history .wp-list-table.task-history .column-status mark {
				background: none;
			}

			/* Fix cursor when list table is displayed in a metabox on the WooCommerce orders screen. */
			#woocommerce-zapier-task-history .wp-list-table.task-history tbody td {
				cursor: default !important;
				padding: 8px 10px;
			}

			#woocommerce-zapier-task-history .wp-list-table.task-history td .woocommerce-help-tip {
				margin: 0;
			}

			/* Help icon change to info icon. */
			#woocommerce-zapier-task-history .wp-list-table.task-history td .woocommerce-help-tip::after {
				content: "\f348";
			}

			/* Status icon colours. */
			#woocommerce-zapier-task-history .wp-list-table.task-history .column-status mark.yes {
				/* Same green as WooCommerce's "Processing" order status */
				color: #5b841b;
			}

			#woocommerce-zapier-task-history .wp-list-table.task-history .column-status mark.error {
				/* Same red as WooCommerce's status report */
				color: #a00;
			}

			/* Ensure WooCommerce help tips (on hover) can be wide enough to display longer messages */
			#tiptip_holder {
				max-width: 50em !important;
			}

			#tiptip_content {
				max-width: none;
				white-space: nowrap;
			}
		</style>
		<?php
		echo '<div id="woocommerce-zapier-task-history">';
		echo '<form id="zapier-task-history" method="get">';
		if ( ! $this->metabox_mode ) {
			self::search_box( __( 'Search Task History', 'woocommerce-zapier' ), 's' );
			echo '<input type="hidden" name="page" value="wc_zapier" />';
			echo '<input type="hidden" name="_wcznonce" value="' . esc_attr( wp_create_nonce( 'zapier-task-history' ) ) . '" />';
		}
		parent::display();
		if ( ! $this->metabox_mode ) {
			echo '</form>';
		}
		echo '</div>';
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
