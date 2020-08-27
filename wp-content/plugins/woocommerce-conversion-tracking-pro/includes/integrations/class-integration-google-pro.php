<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Google adwords
 */
class WCCT_PRO_Integration_Google extends WCCT_Integration_Google {

    /**
     * Constructor for the WCCT_PRO_Integration_Google Class
     */
    function __construct() {
        parent::__construct();
        array_push( $this->supports,
            'registration'
        );

        add_filter( 'wcct_settings_adwords', array( $this, 'field_settings' ), 10, 1 );
    }

    /**
     * Get twitter pro field
     *
     * @return array
     */
    public function field_settings( $settings ) {

        $settings['events']['options']['Registration']  = array(
            'event_label_box'  => true,
            'label'            => __( 'Complete Registration', 'woocommerce-conversion-tracking' ),
            'label_name'       => 'registration-label',
            'placeholder'      => 'Add your registration label'
        );

        return $settings;
    }

    /**
     * Check Out google adwords
     *
     * @return void
     */
    public function registration() {
        if ( ! $this->event_enabled( 'Registration' ) ) {
            return;
        }

        $settings   = $this->get_integration_settings();
        $account_id = isset( $settings[0]['account_id'] ) ? $settings['account_id'] : '';
        $label      = isset( $settings[0]['events']['Registration-label'] ) ? $settings[0]['events']['Registration-label'] : '';

        if ( empty( $account_id ) || empty( $label ) ) {
            return;
        }

        $order = new WC_Order( $order_id );

        $code = $this->build_event( 'Registration', array(
            'send_to'        => sprintf( "%s/%s", $account_id, $label ),
        ) );

        wc_enqueue_js( $code );
    }
}

return new WCCT_PRO_Integration_Google();
