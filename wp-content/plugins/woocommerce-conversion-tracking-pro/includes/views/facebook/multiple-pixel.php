<?php
    // don't call the file directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="inner-setting">

    <?php foreach ( $settings as $index => $setting ) { ?>

    <div class="wc-ct-form-group <?php echo esc_attr( $border ); ?>">

        <table class="form-table custom-table">

            <?php foreach ( $setting_field as $field_key => $field ) {?>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( $id ) . '-' . esc_attr( $field['label'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
                    </th>

                    <td>
                        <?php
                        $placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';

                        switch ( $field['type'] ) {
                            case 'text':
                               $value = isset( $settings[ $index ][ $field['name'] ] ) ? $settings[ $index ][ $field['name'] ] : '';
                                printf( '<input type="text" name="settings[%s][%d][%s]" placeholder="%s" value="%s" id="%s">', esc_attr( $id ), esc_attr( $index ), esc_attr( $field['name'] ), esc_attr( $placeholder ), esc_attr( $value ), esc_attr( $id ) . '-' . esc_attr( $field['name'] ) );
                                break;

                            case 'textarea':
                                $value = isset( $settings[ $index ][ $field['name'] ] ) ? $settings[ $index ][ $field['name'] ] : '';
                                printf( '<textarea type="text" name="settings[%s][%s]" placeholder="%s" id="%s" cols="30" rows="3">%s</textarea>', esc_attr( $id ), esc_attr( $field['name'] ), esc_attr( $placeholder ), esc_attr( $id ) . '-' . esc_attr( $field['name'] ), esc_attr( $value ) );
                                break;

                            case 'checkbox':
                                $value = isset( $settings[ $index ][ $field['name'] ] ) ? $settings[ $index ][ $field['name'] ] : 'off';
                                printf( '<label for="%5$s"><input type="checkbox" name="settings[%1$s][%2$s]" %3$s id="%5$s" value="on"> %4$s</label>', esc_attr( $id ), esc_attr( $field['name'] ), checked( 'on', $value, false ), esc_attr( $field['description'] ), esc_attr( $id ) . '-' . esc_attr( $field['name'] ) );
                                break;

                            case 'multicheck':
                                ?>
                                <div class="wc-ct-option">
                                    <?php
                                    foreach ( $field['options'] as $key => $option ) {
                                        $field_name = $field['name'];

                                        $checked = isset( $settings[$index][ $field_name ][ $key ] ) ? 'on' : '';
                                        ?>
                                            <label for="<?php echo esc_attr( $id ) . '-' . esc_attr( $key ); ?>">
                                                <input type="checkbox" name="settings[<?php echo esc_attr( $id ); ?>][<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $field_name ); ?>][<?php echo esc_attr( $key ); ?>]" <?php checked( 'on', $checked ); ?> id="<?php echo esc_attr( $id ) . '-' . esc_attr( $key ); ?>">
                                                <?php echo esc_html( $option ); ?>
                                            </label>
                                            <br>
                                            <?php
                                    }
                                    ?>
                                </div>
                            <?php
                        }

                        if ( isset( $field['help'] ) && ! empty( $field['help'] ) ) {
                            echo '<p class="help">' . esc_html( $field['help'] ) . '</p>';
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <button id="remove" class="button button-secondary button-small" style="float:right;margin-right: 12px;margin-top: -30px;"><span class="dashicons dashicons-trash"></span></button>
    </div>

<?php
    }
?>
</div>
<div class="wcct-add-new">
    <button class="button button-secondary button-small" id="add-new-pixel"><span class="dashicons dashicons-plus-alt"></span> <?php esc_html_e( 'Add New', 'woocommerce-conversion-tracking-pro' ); ?></button>
</div>
