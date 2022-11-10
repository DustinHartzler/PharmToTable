<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting;

class Main {
	const REST_NAMESPACE = 'trd/v1';

	public static function init() {
		static::includes();

		Hooks::register();

		Privacy::init();
	}

	public static function includes() {
		require_once __DIR__ . '/traits/trait-event.php';
		require_once __DIR__ . '/traits/trait-report.php';
		require_once __DIR__ . '/traits/trait-singleton.php';

		require_once __DIR__ . '/class-hooks.php';
		require_once __DIR__ . '/class-logs.php';
		require_once __DIR__ . '/class-store.php';
		require_once __DIR__ . '/class-privacy.php';

		require_once __DIR__ . '/abstract/class-event.php';
		require_once __DIR__ . '/abstract/class-event-field.php';

		require_once __DIR__ . '/abstract/class-report-app.php';
		require_once __DIR__ . '/abstract/class-report-type.php';

		require_once __DIR__ . '/event-fields/class-created.php';
		require_once __DIR__ . '/event-fields/class-item-id.php';
		require_once __DIR__ . '/event-fields/class-post-id.php';
		require_once __DIR__ . '/event-fields/class-user-id.php';
	}
}
