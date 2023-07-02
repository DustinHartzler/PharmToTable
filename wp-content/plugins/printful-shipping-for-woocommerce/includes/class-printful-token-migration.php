<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Printful_Token_Migration
{
    private const OPTION_NAME_MIGRATION = 'pf-migration-in-progress';

    public static $_instance;

    private $integration;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        self::$_instance = $this;

        $this->integration = Printful_Integration::instance();
    }

    public static function init() {
        $instance = self::instance();

        if ($instance->shouldMigrate()) {
            try {
                $instance->startMigration();
                $instance->migrate();
            } catch (Throwable $throwable) {
                $instance->restartMigration();
                // allow migration to silently fail
            }
        }
    }

    public function shouldMigrate() {
        $restKey = $this->integration->get_option( 'printful_key' );
        $oauthKey = $this->integration->get_option( 'printful_oauth_key' );

        return $restKey && !$oauthKey && !$this->isMigrationRunning();
    }

    public function migrate() {

        $client = $this->integration->get_client();

        $response = $client->post('integration-plugin/get-o-auth-credentials');

        if (isset($response['token'])) {
            $options = get_option( 'woocommerce_printful_settings', array() );

            $options['printful_oauth_key'] = $response['token'];

            $this->integration->update_settings( $options );

            $oauth_client = $this->integration->get_client();

            $response = $oauth_client->post('integration-plugin/finalize-migration');

            if (isset($response['status']) && $response['status'] === 1) {
                unset($options['printful_key']);
                $this->integration->update_settings( $options );
            }
        }
    }

    protected function isMigrationRunning()
    {
        return (bool) get_option(self::OPTION_NAME_MIGRATION, 0);
    }

    protected function startMigration()
    {
        update_option(self::OPTION_NAME_MIGRATION, 1);
    }

    protected function restartMigration()
    {
        update_option(self::OPTION_NAME_MIGRATION, 0);
    }
}