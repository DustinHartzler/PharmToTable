<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-contestant-db.php';

class KS_Contestants_List_Table extends WP_List_Table
{
    public $contest_id = null;
    public $blocked_ips = null;

    public function __construct($args = array())
    {
        $this->contest_id = $args['contest_id'];

        parent::__construct(array(
            'plural' => 'contestants',
            'singular' => 'contestant',
            'screen' => null
        ));
    }

    public function current_action() {
        if (isset($_REQUEST['downloadcsv'])) {
            return 'downloadcsv';

        } elseif (isset($_REQUEST['bulkremove'])) {
            return 'bulkremove';

        } elseif (isset($_REQUEST['bulkresend'])) {
            return 'bulkresend';

        } elseif (isset($_REQUEST['blockip'])) {
            return 'blockip';

        } elseif (isset($_REQUEST['unblockip'])) {
            return 'unblockip';
        }

        return parent::current_action();
    }

    public function bulk_actions($which = '')
    {
        ?>
            <label class="screen-reader-text">Select bulk action</label>
            <select name="bulk-action-<?php echo $which; ?>" id="bulk-action-selector-<?php echo $which; ?>">
                <option value="-1">Bulk Actions</option>
                <option value="remove" class="hide-if-no-js">Remove</option>
                <option value="resend">Resend Confirmation</option>
            </select>
            <input type="submit" id="doaction2" class="button action" value="Apply">
        <?php
    }

    public function prepare_items()
    {
        $this->blocked_ips = get_post_meta($this->contest_id, '_blocked_ips', true);

        $id = (int) $_REQUEST['id'];
        $paged = max(1, isset($_REQUEST['paged']) ? (int) $_REQUEST['paged'] : 1);
        $per_page = 10;
        $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'date_added';
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';

        if (!in_array($orderby, array('date_added', 'num_entries', 'email_address'))) {
            $orderby = 'date_added';
        }

        if (!in_array($order, array('asc','desc'))) {
            $order = 'desc';
        }

        $offset = ($paged - 1) * $per_page;

        // Handle search
        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $search = $_REQUEST['s'];

        } else {
            $search = null;
        }

        $total = KS_Contestant_DB::get_total($id, $search);
        $results = KS_Contestant_DB::get_results($id, $offset, $per_page, $orderby, $order, $search);
        //print_r($results);

        $this->items = $results;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->set_pagination_args(array(
            'total_items' => $total,
            'per_page' => $per_page
        ));
    }

    public function column_default($item, $column_name)
    {
        switch($column_name) {
            case 'date_added': return date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime(get_date_from_gmt($item[$column_name])));
            case 'status': return ucwords($item['status']);
            case 'ip_address':
                if (is_array($this->blocked_ips) && in_array($item[$column_name], $this->blocked_ips)) {
                    $blockLinkUrl = wp_nonce_url(admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $this->contest_id) . '&unblockip=' . $item[$column_name], 'unblockip');
                    $blockLinkTitle = esc_attr("Unblock new contestants from " . $item[$column_name]);
                    return $item[$column_name] . sprintf(' (<a href="%s" style="color:#a00" title="%s">Unblock</a>)', $blockLinkUrl, $blockLinkTitle);

                } else {
                    $blockLinkUrl = wp_nonce_url(admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $this->contest_id) . '&blockip=' . $item[$column_name], 'blockip');
                    $blockLinkTitle = esc_attr("Block new contestants from " . $item[$column_name]);
                    return $item[$column_name] . sprintf(' (<a href="%s" style="color:#a00" title="%s">Block</a>)', $blockLinkUrl, $blockLinkTitle);
                }
            /*
            case 'actions':
                $form = '<form method="post" style="padding:0;margin:0;display:inline;"><input type="hidden" name="contestant_id" value="'.$item['ID'].'" />%s</form>';
                $ret = array();
                $ret[] = '<button name="post_action" value="remove" class="button button-small" title="Remove contestant from giveaway" onclick="return confirm(\'Are you sure you want to remove this contestant?\');">Remove</button>';

                if ($this->draw_mode === 'confirmed') {
                    $ret[] = '<button name="post_action" value="resend" class="button button-small" title="Resend confirmation email">Resend</button>';
                }
                
                if (count($ret) > 1) {
                    return sprintf($form, '<div class="button-group">' . implode('', $ret) . '</div>');
                } else {
                    return sprintf($form, implode('&nbsp;', $ret));
                }
            */

            default: return $item[$column_name];
        }
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="selected_contestants[]" value="%s" />', $item['ID']
        );    
    }

    public function get_columns()
    {
        $columns = array();
        $columns['cb'] = '<input type="checkbox" />';
        $columns['email_address'] = 'Email';
        if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)) {
            $columns['first_name'] = 'First Name';
        }
        $columns['num_entries'] = 'Entries';
        $columns['date_added'] = 'Date Entered';
        $columns['ip_address'] = 'IP Address';
        $columns['status'] = 'Status';
        //$columns['actions'] = 'Actions';

        return $columns;
    }

    public function get_sortable_columns()
    {
        return array(
            'email_address' => array('email_address', false),
            'num_entries' => array('num_entries', false),
            'date_added' => array('date_added', true),
            'ip_address' => array('ip_address', false),
            'first_name' => array('first_name', false),
            'status' => array('status', false)
        );
    }

    public function no_items()
    {
        _e('No contestants found.');
    }
}