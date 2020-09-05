<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-database.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-contestant-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';

class KS_Entry_DB extends KS_Database_Table
{
    protected static $table_name = 'ks_giveaways_entry';

    public static function install_table()
    {
        $table = self::get_tablename();

        $sql = "CREATE TABLE {$table} (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  contestant_id bigint(20) unsigned NOT NULL,
  referral_id bigint(20) unsigned,
  action_id varchar(40) CHARACTER SET utf8 NULL,
  link_clicked varchar(255) CHARACTER SET utf8 NULL,
  ip_address varchar(46) CHARACTER SET utf8 NOT NULL,
  date_added timestamp NULL,
  PRIMARY KEY  (ID),
  KEY entry_contestant (contestant_id),
  KEY entry_referral (referral_id),
  KEY entry_contestant_referral (contestant_id,referral_id),
  KEY entry_link_clicked (link_clicked),
  KEY entry_action_id (action_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

        $ret = dbDelta($sql);
    }

    public static function get_results($contestant_id, $fields = array('*'))
    {
        global $wpdb;

        $table = self::get_tablename();
        $fields = join(',', $fields);

        $query = $wpdb->prepare("SELECT {$fields} FROM {$table} WHERE `contestant_id` = %d", $contestant_id);

        return $wpdb->get_results($query, ARRAY_A);
    }

    public static function get_total($contestant_id)
    {
        global $wpdb;

        $table = self::get_tablename();
        $contestant_table = KS_Contestant_DB::get_tablename();

        // Possible values: 'all' or 'confirmed'
        $mode = get_option(KS_GIVEAWAYS_OPTION_DRAW_MODE, 'all');

        if($mode === "confirmed")
        {
            /* Template - remove comment for IDE intelligence
            $_ = "
            SELECT COUNT(wp_ks_giveaways_contestant.ID)
            FROM wp_ks_giveaways_entry
                JOIN wp_ks_giveaways_contestant
                    ON wp_ks_giveaways_contestant.ID = wp_ks_giveaways_entry.contestant_id
                LEFT JOIN wp_ks_giveaways_contestant AS referred_contestant
                    ON wp_ks_giveaways_entry.referral_id = referred_contestant.ID
            WHERE wp_ks_giveaways_contestant.ID = 1350
                  AND (
                      wp_ks_giveaways_entry.referral_id IS NULL
                      OR referred_contestant.status = 'confirmed'
                      OR wp_ks_giveaways_contestant.ID > wp_ks_giveaways_entry.referral_id
                  )
                  AND wp_ks_giveaways_contestant.status = 'confirmed';
            "; */

            $query = "
            SELECT COUNT({$contestant_table}.ID)
            FROM {$table}
                JOIN {$contestant_table}
                    ON {$contestant_table}.ID = {$table}.contestant_id
                LEFT JOIN {$contestant_table} AS referred_contestant
                    ON {$table}.referral_id = referred_contestant.ID
            WHERE {$contestant_table}.ID = %d
                  AND (
                      {$table}.referral_id IS NULL
                      OR referred_contestant.status = 'confirmed'
                      OR {$contestant_table}.ID > {$table}.referral_id
                  )
                  AND {$contestant_table}.status = 'confirmed';
            ";

            $query = trim($wpdb->prepare($query, $contestant_id));
        }
        else
        {
            $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contestant_id` = %d", $contestant_id);
        }

        return (int) $wpdb->get_var($query);
    }

    public static function remove_contestant($contestant_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('contestant_id' => $contestant_id);
        $format = array('%d');
        $wpdb->delete($table, $data, $format);

        // remove referrer on entries
        $data = array('referral_id' => 'NULL');
        $where = array('referral_id' => $contestant_id);
        $where_format = array('%d');

        add_filter('query', array(__CLASS__, 'wpse_143405_query'));
        $wpdb->update($table, $data, $where, null, $where_format);
        remove_filter('query', array(__CLASS__, 'wpse_143405_query'));
    }

    public static function wpse_143405_query($query)
    {
        return str_ireplace("'NULL'", "NULL", $query);
    }

    public static function get_contest_total($contest_id)
    {
        global $wpdb;

        $table = self::get_tablename();
        $contestant_table = KS_Contestant_DB::get_tablename();

        // Possible values: 'all' or 'confirmed'
        $mode = get_option(KS_GIVEAWAYS_OPTION_DRAW_MODE, 'all');

        if($mode === "confirmed")
        {
            /* Template - remove comment for IDE intelligence
            $_ = "
            SELECT COUNT(wp_ks_giveaways_contestant.ID)
            FROM wp_ks_giveaways_entry
                JOIN wp_ks_giveaways_contestant
                    ON wp_ks_giveaways_contestant.ID = wp_ks_giveaways_entry.contestant_id
                LEFT JOIN wp_ks_giveaways_contestant AS referred_contestant
                    ON wp_ks_giveaways_entry.referral_id = referred_contestant.ID
            WHERE wp_ks_giveaways_contestant.contest_id = 6
                  AND (
                      wp_ks_giveaways_entry.referral_id IS NULL
                      OR referred_contestant.status = 'confirmed'
                      OR wp_ks_giveaways_contestant.ID > wp_ks_giveaways_entry.referral_id
                  )
                  AND wp_ks_giveaways_contestant.status = 'confirmed';
            "; */

            $query = "
            SELECT COUNT({$contestant_table}.ID)
            FROM {$table}
                JOIN {$contestant_table}
                    ON {$contestant_table}.ID = {$table}.contestant_id
                LEFT JOIN {$contestant_table} AS referred_contestant
                    ON {$table}.referral_id = referred_contestant.ID
            WHERE {$contestant_table}.contest_id = %d
                  AND (
                      {$table}.referral_id IS NULL
                      OR referred_contestant.status = 'confirmed'
                      OR {$contestant_table}.ID > {$table}.referral_id
                  )
                  AND {$contestant_table}.status = 'confirmed';
            ";

            $query = trim($wpdb->prepare($query, $contest_id));
        }
        else
        {
            $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} JOIN {$contestant_table} ON {$contestant_table}.`ID` = {$table}.`contestant_id` WHERE {$contestant_table}.`contest_id` = %d", $contest_id);
        }

        return (int) $wpdb->get_var($query);
    }

    public static function draw($contest_id, $overwrite_id = null, $exclude = null)
    {
        global $wpdb;

        $table = self::get_tablename();
        $contestant_table = KS_Contestant_DB::get_tablename();
        $winner_table = KS_Winner_DB::get_tablename();

        // Possible values: 'all' or 'confirmed'
        $mode = get_option(KS_GIVEAWAYS_OPTION_DRAW_MODE, 'all');

        if($mode === "confirmed")
        {
            /* Template - remove comment for IDE intelligence
            $_ = "
            SELECT COUNT(wp_ks_giveaways_contestant.ID)
            FROM wp_ks_giveaways_entry
                JOIN wp_ks_giveaways_contestant
                    ON wp_ks_giveaways_contestant.ID = wp_ks_giveaways_entry.contestant_id
                LEFT JOIN wp_ks_giveaways_contestant AS referred_contestant
                    ON wp_ks_giveaways_entry.referral_id = referred_contestant.ID
            WHERE wp_ks_giveaways_contestant.contest_id = 4
                  AND (
                       wp_ks_giveaways_entry.referral_id IS NULL
                       OR referred_contestant.status = 'confirmed'
                       OR wp_ks_giveaways_contestant.ID > wp_ks_giveaways_entry.referral_id
                  )
                  AND wp_ks_giveaways_contestant.status = 'confirmed'
                  AND wp_ks_giveaways_contestant.ID
                      NOT IN (
                          SELECT contestant_id
                          FROM wp_ks_giveaways_winner
                          WHERE contest_id = 4
                      );
            "; */

            $query = "
            SELECT COUNT({$contestant_table}.ID)
            FROM {$table}
                JOIN {$contestant_table}
                    ON {$contestant_table}.ID = {$table}.contestant_id
                LEFT JOIN {$contestant_table} AS referred_contestant
                    ON {$table}.referral_id = referred_contestant.ID
            WHERE {$contestant_table}.contest_id = %d
                  AND (
                       {$table}.referral_id IS NULL
                       OR referred_contestant.status = 'confirmed'
                       OR {$contestant_table}.ID > {$table}.referral_id
                  )
                  AND {$contestant_table}.status = 'confirmed'
                  AND {$contestant_table}.ID
                      NOT IN (
                          SELECT contestant_id
                          FROM {$winner_table}
                          WHERE contest_id = %d
                      );
            ";

            $query = trim($wpdb->prepare($query, $contest_id, $contest_id));

            $count = (int) $wpdb->get_var($query);
            $offset = rand(0, $count - 1);

            /* Template - remove comment for IDE intelligence
            $_ = "
            SELECT wp_ks_giveaways_contestant.*
            FROM wp_ks_giveaways_entry
                JOIN wp_ks_giveaways_contestant
                    ON wp_ks_giveaways_contestant.ID = wp_ks_giveaways_entry.contestant_id
                LEFT JOIN wp_ks_giveaways_contestant AS referred_contestant
                    ON wp_ks_giveaways_entry.referral_id = referred_contestant.ID
            WHERE wp_ks_giveaways_contestant.contest_id = 4
                  AND (
                       wp_ks_giveaways_entry.referral_id IS NULL
                       OR referred_contestant.status = 'confirmed'
                       OR wp_ks_giveaways_contestant.ID > wp_ks_giveaways_entry.referral_id
                  )
                  AND wp_ks_giveaways_contestant.status = 'confirmed'
                  AND wp_ks_giveaways_contestant.ID
                      NOT IN (
                          SELECT contestant_id
                          FROM wp_ks_giveaways_winner
                          WHERE contest_id = 4
                      )
            LIMIT 5, 1;
            "; */

            $query = "
            SELECT {$contestant_table}.*
            FROM {$table}
                JOIN {$contestant_table}
                    ON {$contestant_table}.ID = {$table}.contestant_id
                LEFT JOIN {$contestant_table} AS referred_contestant
                    ON {$table}.referral_id = referred_contestant.ID
            WHERE {$contestant_table}.contest_id = %d
                  AND (
                       {$table}.referral_id IS NULL
                       OR referred_contestant.status = 'confirmed'
                       OR {$contestant_table}.ID > {$table}.referral_id
                  )
                  AND {$contestant_table}.status = 'confirmed'
                  AND {$contestant_table}.ID
                      NOT IN (
                          SELECT contestant_id
                          FROM {$winner_table}
                          WHERE contest_id = %d
                      )
            LIMIT {$offset}, 1;
            ";

            $query = trim($wpdb->prepare($query, $contest_id, $contest_id));
        }
        else
        {
            $query = trim($wpdb->prepare("
              SELECT COUNT({$table}.`ID`) FROM {$table} JOIN {$contestant_table} ON {$contestant_table}.`ID` = {$table}.`contestant_id` WHERE {$contestant_table}.`contest_id` = %d
              AND {$contestant_table}.`ID` NOT IN (SELECT `contestant_id` FROM {$winner_table} WHERE `contest_id` = %d)
            ", $contest_id, $contest_id));

            $count = (int) $wpdb->get_var($query);
            $offset = rand(0, $count - 1);

            $query = trim($wpdb->prepare("
              SELECT {$contestant_table}.* FROM {$table} JOIN {$contestant_table} ON {$contestant_table}.`ID` = {$table}.`contestant_id` WHERE {$contestant_table}.`contest_id` = %d
              AND {$contestant_table}.`ID` NOT IN (SELECT `contestant_id` FROM {$winner_table} WHERE `contest_id` = %d)
              LIMIT {$offset},1
            ", $contest_id, $contest_id));
        }

        $contestant = $wpdb->get_row($query);

        if ($contestant) {
            if ($overwrite_id) {
                KS_Winner_DB::replace_winner($contest_id, $overwrite_id, $contestant);
            } else {
                KS_Winner_DB::insert_winner($contest_id, $contestant);
            }
        }
    }

    /**
     * Adds entry(s) for specified contestant.
     * @param $contestant_id
     * @param $referral_id
     * @param $number_of_entries - how many entries to add.
     * @param $link_clicked - URL of link click which was awarded entries.
     */
    public static function add($contestant_id, $referral_id, $number_of_entries = 1, $action_id = NULL)
    {
        global $wpdb;

        $table = self::get_tablename();
        $data = array(
            'contestant_id' => $contestant_id,
            'ip_address'    => substr($_SERVER['REMOTE_ADDR'], 0, 46),
            'date_added'    => current_time('mysql', true),
            'action_id'     => $action_id
        );

        $format = array('%d', '%s', '%s', '%s');

        if ($referral_id) {
            $data['referral_id'] = $referral_id;
            $format[] = '%d';
        }

        if ($number_of_entries >= 1) {
          foreach (range(1, $number_of_entries) as $entry_count) {
            if ( ! $wpdb->insert($table, $data, $format)) {
                return false;
            }
          }
        }

        return true;
    }

    public static function has_referral($contestant_id, $referral_id)
    {
        global $wpdb;

        $table = self::get_tablename();
        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contestant_id` = %d AND `referral_id` = %d", $contestant_id, $referral_id);

        return ( (int) $wpdb->get_var($query) > 0);
    }

    // Depricated
    public static function get_link_entry_count($contestant_id, $link) {
      global $wpdb;

      $table = self::get_tablename();
      $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contestant_id` = %d AND `link_clicked` = %s", $contestant_id, $link);

      return $wpdb->get_var($query);
    }

    public static function get_action_entry_count($contestant_id, $action_id) {
      global $wpdb;

      $table = self::get_tablename();
      $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contestant_id` = %d AND `action_id` = %s", $contestant_id, $action_id);

      return $wpdb->get_var($query);
    }

    public static function get_all_action_entries_count($contestant_id) {
      global $wpdb;

      $table = self::get_tablename();
      $query = $wpdb->prepare("SELECT DISTINCT(`action_id`), COUNT(`action_id`) as `entries` FROM {$table} WHERE `contestant_id` = %d AND `action_id` IS NOT NULL GROUP BY `action_id`", $contestant_id);

      return $wpdb->get_results($query, ARRAY_A);
    }
}