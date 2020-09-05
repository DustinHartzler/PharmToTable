<?php

class KS_Helper
{
    public static function replace_shortcodes($subject, $post = null, $contestant = null)
    {
        $search = array(
            'name',
            'site_name',
            'prize_value',
            'prize_name',
            'prize_brand',
            'date_awarded',
            'date_ended',
            'date_end',
            'contact_email',
            'site_url',
            'lucky_url',
            'confirm_url',
            'entries_per_friend',
            'address_street',
            'address_city',
            'address_state',
            'address_country',
            'address_zip'
        );

        $replace = array(
            self::get_title($post),
            get_bloginfo('name'),
            self::get_prize_value($post),
            self::get_prize_name($post),
            self::get_prize_brand($post),
            self::get_date_text(self::get_date_awarded()),
            self::get_date_text(self::get_date_end()),
            self::get_date_text(self::get_date_end()),
            get_bloginfo('admin_email'),
            get_bloginfo('wpurl'),
            self::get_lucky_url($post, $contestant),
            self::get_confirm_url($post, $contestant),
            self::get_entries_per_friend($post),
            get_option(KS_GIVEAWAYS_OPTION_ADDRESS_STREET),
            get_option(KS_GIVEAWAYS_OPTION_ADDRESS_CITY),
            get_option(KS_GIVEAWAYS_OPTION_ADDRESS_STATE),
            get_option(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY),
            get_option(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP)
        );

        if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)) {
            $search[] = 'first_name';
            $replace[] = self::get_first_name($post, $contestant);
        }

        foreach ($search as $index => $key) {
            $value = $replace[$index];
            if ($value) {
                $subject = str_replace(array(
                    '[#' . $key . ']',
                    '[/' . $key . ']',
                    '[' . $key . ']'
                ), array(
                    '',
                    '',
                    $value
                ), $subject);
            } else {
                // remove ifs
                $re = '/\[#' . $key . '\].*?\[\/' . $key . '\]/is';
                $subject = preg_replace($re, '', $subject);

                // remove empty placeholders
                $subject = str_replace('[' . $key .']', '', $subject);
            }
        }

        return $subject;
    }

    public static function get_date_text($epoch)
    {
        $offset = get_option('gmt_offset');

        return sprintf('%s %s', gmdate('F d, h:iA', $epoch+($offset*3600)), self::get_timezone_abbr());
    }

    public static function send_confirm_email($contestant, $post = null)
    {
        //$from = filter_var(get_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS), FILTER_VALIDATE_EMAIL);
        $from = get_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS);
        //$reply_to = filter_var(get_option(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS), FILTER_VALIDATE_EMAIL);
        $reply_to = get_option(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS);
        $subject = self::replace_shortcodes(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT), $post, $contestant);
        $body = wpautop(self::replace_shortcodes(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY), $post, $contestant));

        $show_badge = get_option(KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE);
        if ($show_badge === false || (int) $show_badge === 1) {
            $body .= '<br><br><p>' . __('Powered by', KS_GIVEAWAYS_TEXT_DOMAIN) . ' <a href="http://kingsumo.com/apps/giveaways/">KingSumo Giveaways</a></p>';
        }
        
        $headers = array(
          'Content-type: text/html'
        );

        if ($from) {
            $headers[] = sprintf('From: %s', $from);
        }

        if ($reply_to) {
            $headers[] = sprintf('Reply-To: %s', $reply_to);
        }

        wp_mail($contestant->email_address, $subject, $body, $headers);
    }

    public static function send_winner_email($winner, $post = null)
    {
        $contestant = KS_Contestant_DB::get($winner->contestant_id);

        //$from = filter_var(get_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS), FILTER_VALIDATE_EMAIL);
        $from = get_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS);
        //$reply_to = filter_var(get_option(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS), FILTER_VALIDATE_EMAIL);
        $reply_to = get_option(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS);
        $subject = self::replace_shortcodes(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT), $post, $contestant);
        $body = wpautop(self::replace_shortcodes(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY), $post, $contestant));

        $show_badge = get_option(KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE);
        if ($show_badge === false || (int) $show_badge === 1) {
            $body .= '<br><br><p>' . __('Powered by', KS_GIVEAWAYS_TEXT_DOMAIN) . ' <a href="http://kingsumo.com/apps/giveaways/">KingSumo Giveaways</a></p>';
        }
        
        $headers = array(
          'Content-type: text/html'
        );

        if ($from) {
            $headers[] = sprintf('From: %s', $from);
        }

        if ($reply_to) {
            $headers[] = sprintf('Reply-To: %s', $reply_to);
        }

        wp_mail($winner->email_address, $subject, $body, $headers);
    }

    public static function ensure_fqdn($url)
    {
        // extract URL parts
        $url_parts = parse_url($url);

        // store our server scheme
        $scheme = ((array_key_exists('HTTPS', $_SERVER)
                              && $_SERVER['HTTPS']) ||
                          (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER)
                                  && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';

        // fill in missing parts to generate absolute URL
        if (!isset($url_parts['port']) && !isset($url_parts['scheme'])) $url_parts['port'] = $_SERVER['SERVER_PORT'];
        if (!isset($url_parts['scheme'])) $url_parts['scheme'] = $scheme;
        if (!isset($url_parts['user']) && isset($_SERVER['PHP_AUTH_USER'])) $url_parts['user'] = $_SERVER['PHP_AUTH_USER'];
        if (!isset($url_parts['pass']) && isset($_SERVER['PHP_AUTH_PW'])) $url_parts['pass'] = $_SERVER['PHP_AUTH_PW'];
        if (!isset($url_parts['host'])) $url_parts['host'] = $_SERVER['HTTP_HOST'];

        // remove port if standard port
        if (isset($url_parts['port'])) {
            if ($url_parts['scheme'] == 'http' && $url_parts['port'] == 80) unset($url_parts['port']);
            else if ($url_parts['scheme'] == 'https' && $url_parts['port'] == 443) unset($url_parts['port']);
        }

        // reassemble URL from parts
        $url = '';

        if (!empty($url_parts['scheme'])) {
			$url .= $url_parts['scheme'] . '://';
		}
		if (!empty($url_parts['user'])) {
			$url .= $url_parts['user'];
			if (isset($url_parts['pass'])) {
				$url .= ':' . $url_parts['pass'];
			}
			$url .= '@';
		}
		if (!empty($url_parts['host'])) {
			$url .= $url_parts['host'];
		}
		if (!empty($url_parts['port'])) {
			$url .= ':' . $url_parts['port'];
		}
		if (!empty($url_parts['path'])) {
			$url .= $url_parts['path'];
		}
		if (!empty($url_parts['query'])) {
			$url .= '?' . $url_parts['query'];
		}
		if (!empty($url_parts['fragment'])) {
			$url .= '#' . $url_parts['fragment'];
		}

		return $url;
    }

    public static function get_confirm_url($post = null, $contestant)
    {
        if (is_null($post)) {
            $post = get_post();

        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        // Check if an embed post ID is supplied, if so use that for the confirm link.
        $embed_post_id = trim(get_post_meta($post->ID, '_embed_post_id', true));

        if ($embed_post_id > 0) {
            $url = get_permalink($embed_post_id);

        } else {
            $url = get_permalink($post->ID);
        }

        if ($contestant) {
            $url = add_query_arg('confirm', $contestant->ID, $url);
            $url = add_query_arg('key', $contestant->confirm_key, $url);
            return self::ensure_fqdn($url);
        }
    }

    /**
     * @param $post
     * @param $contestant
     * @param $share BOOL If this link is used for share urls
     */
    public static function get_lucky_url($post = null, $contestant = null, $share = false)
    {
        if (is_null($contestant) && !empty($GLOBALS['ks_giveaways_contestant'])) {
            $contestant = $GLOBALS['ks_giveaways_contestant'];
        }

        if (is_null($post)) {
            $post = get_post();

        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        if ($contestant) {
            if (array_key_exists('embed_post', $_GET) && $share) {
                $url = get_permalink($_GET['embed_post']);

            } else {
                $url = get_permalink($post->ID);
            }

            $url = add_query_arg('lucky', $contestant->ID, $url);

            // Carry over embed parameter if needed
            if (array_key_exists('embed_post', $_GET) && ! $share) {
                $url = add_query_arg('embed_post', $_GET['embed_post'], $url);
            }

            return self::ensure_fqdn($url);
        }
    }

    public static function get_my_entries()
    {
        if (!empty($GLOBALS['ks_giveaways_contestant'])) {
            return KS_Entry_DB::get_total($GLOBALS['ks_giveaways_contestant']->ID);
        }
    }

    public static function get_contestant_entries($contestant_id)
    {
        return KS_Entry_DB::get_results($contestant_id, array('link_clicked'));
    }

    public static function time_between($start, $end)
    {
        $seconds = $end - $start;

        $days = floor($seconds / 86400);
        $seconds %= 86400;
        $hours = floor($seconds / 3600);
        $seconds %= 3600;
        $minutes = floor($seconds / 60);
        $seconds %= 60;

        $ret = array();
        if ($days) {
          $ret[] = sprintf('%d', $days).'d';
        }
        if ($hours) {
          $ret[] = sprintf('%d', $hours).'h';
        }
        if ($minutes) {
          $ret[] = sprintf('%d', $minutes).'m';
        }
        $ret[] = sprintf('%d', $seconds).'s';

        return implode('', $ret);
    }

    public static function is_running($post = null)
    {
        return (self::has_started($post) && !self::has_ended($post));
    }

    public static function has_started($post = null)
    {
        $start = self::get_date_start($post);
        if ($start && $start <= time()) {
            return true;
        }

        return false;
    }

    public static function has_ended($post = null)
    {
        $end = self::get_date_end($post);
        if ($end && time() >= $end) {
            return true;
        }

        return false;
    }

    public static function validate_giveaway($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        if (strlen(self::get_description($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_rules($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_date_start($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_date_end($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_date_awarded($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_entries_per_friend($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_prize_name($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_prize_value($post)) <= 0) {
            return false;
        }

        if (strlen(self::get_winner_count($post)) <= 0) {
            return false;
        }

        if ((int) self::get_enable_question($post) === 1) {
            if (strlen(self::get_question($post)) <= 0) {
                return false;
            }

            if (strlen(self::get_wrong_answer1($post)) <= 0) {
                return false;
            }

            if (strlen(self::get_wrong_answer2($post)) <= 0) {
                return false;
            }

            if (strlen(self::get_right_answer($post)) <= 0) {
                return false;
            }
        }

        return true;
    }

    public static function validate_giveaway_imagelinks($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        $link1 = get_post_meta($post->ID, '_image_1_link', true);
        $link2 = get_post_meta($post->ID, '_image_2_link', true);
        $link3 = get_post_meta($post->ID, '_image_3_link', true);

        if($link1 !== "" and filter_var($link1, FILTER_VALIDATE_URL) === false)
        {
            return false;
        }

        if($link2 !== "" and filter_var($link2, FILTER_VALIDATE_URL) === false)
        {
            return false;
        }

        if($link3 !== "" and filter_var($link3, FILTER_VALIDATE_URL) === false)
        {
            return false;
        }

        return true;
    }

    public static function get_winner_count($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return max(1, get_post_meta($post->ID, '_winner_count', true));
    }

    public static function get_winners($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        $results = KS_Winner_DB::get_results($post->ID);
        $count = self::get_winner_count($post->ID);
        for ($i = count($results); $i < $count; $i++) {
            $results[] = array(
                'email_address' => '',
                'status' => 'pending'
            );
        }

        return $results;
    }

    public static function get_total_entries($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return KS_Entry_DB::get_contest_total($post->ID);
    }

    public static function get_total_winners($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return KS_Winner_DB::get_total($post->ID);
    }

    public static function get_entries_per_friend($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        $entries = get_post_meta($post->ID, '_entries_per_friend', true);
        if (!$entries) {
            return 3;
        }

        return max(1, (int) $entries);
    }

    public static function get_youtube_channel_url()
    {
        return get_option(KS_GIVEAWAYS_OPTION_YOUTUBE_URL);
    }

    public static function get_instagram_url()
    {
        return get_option(KS_GIVEAWAYS_OPTION_INSTAGRAM_URL);
    }


    public static function get_entries_per_youtube_subscribe($post = null)
    {
        if (null === $post) {
            $post = get_post();

        } else if ( ! is_object($post)) {
            $post = get_post($post);
        }

        $entries = get_post_meta($post->ID, '_entries_per_youtube_subscribe', true);

        if (!$entries) {
            $entries = 0;
        }

        return $entries;
    }

    public static function get_entries_per_instagram_follow($post = null)
    {
        if (null === $post) {
            $post = get_post();

        } else if ( ! is_object($post)) {
            $post = get_post($post);
        }

        $entries = get_post_meta($post->ID, '_entries_per_instagram_follow', true);

        if (!$entries) {
            $entries = 0;
        }

        return $entries;
    }

    public static function get_entries_per_twitter_follow($post = null)
    {
        if (null === $post) {
            $post = get_post();

        } else if ( ! is_object($post)) {
            $post = get_post($post);
        }

        $entries = get_post_meta($post->ID, '_entries_per_twitter_follow', true);

        if (!$entries) {
            $entries = 0;
        }

        return $entries;
    }

    public static function get_entries_per_facebook_like($post = null)
    {
        if (null === $post) {
            $post = get_post();

        } else if ( ! is_object($post)) {
            $post = get_post($post);
        }

        $entries = get_post_meta($post->ID, '_entries_per_facebook_like', true);

        if (!$entries) {
            $entries = 0;
        }

        return $entries;
    }

    public static function get_link_entry_count($url)
    {
        return KS_Entry_DB::get_link_entry_count($GLOBALS['ks_giveaways_contestant']->ID, $url);
    }

    public static function get_prize_name($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_prize_name', true);
    }

    public static function get_title($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return $post->post_title;
    }

    public static function get_prize_brand($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_prize_brand', true);
    }

    public static function get_prize_value($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_prize_value', true);
    }

    public static function get_enable_question($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_enable_question', true);
    }

    public static function get_question($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_question', true);
    }

    public static function get_wrong_answer1($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_wrong_answer1', true);
    }

    public static function get_wrong_answer2($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_wrong_answer2', true);
    }

    public static function get_right_answer($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_right_answer', true);
    }

    public static function get_description($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_contest_description', true);
    }

    public static function get_rules($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_contest_rules', true);
    }

    public static function get_date_start($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_date_start', true);
    }

    public static function get_date_end($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_date_end', true);
    }

    public static function get_date_awarded($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        return get_post_meta($post->ID, '_date_awarded', true);
    }

    public static function get_timezone_city($offset = null)
    {
        if (null === $offset) {
            $offset = get_option('gmt_offset');
        }

        if ($offset == 0) {
            return 'UTC';
        }

        $city = get_option('timezone_string');
        if ($city) {
            return $city;
        }

        $offset *= 3600; // convert hour offset to seconds
        $abbrarray = timezone_abbreviations_list();
        foreach ($abbrarray as $abbr)
        {
            foreach ($abbr as $city)
            {
                if ($city['offset'] == $offset)
                {
                  return $city['timezone_id'];
                }
            }
        }

        return false;
    }

    public static function get_timezone_abbr($offset = null)
    {
        if (null === $offset) {
            $offset = get_option('gmt_offset');
        }

        if ($offset == 0) {
            return 'UTC';
        }

        $city = self::get_timezone_city($offset);

        if ($city) {
            $dt = new DateTime();
            $dt->setTimeZone(new DateTimeZone($city));
            return $dt->format('T');
        }

        return false;

    }

    public static function get_template_file($post = null)
    {
        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        $template_file = get_post_meta($post->ID, '_template_file', true);
        $search = array(
            '%wp_content_dir%'
        );
        $replace = array(
            untrailingslashit(WP_CONTENT_DIR)
        );

        $template_file = str_replace($search, $replace, $template_file);

        return $template_file;
    }

    public static function sanitise_first_name($name)
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $escaped = htmlspecialchars($name);
        $escaped = $wpdb->_real_escape($escaped);

        return $escaped; // Should be enough to prevent SQL Injection and XSS
    }

    public static function get_first_name($post = null, $contestant = null)
    {
        if ($contestant == null && !empty($GLOBALS['ks_giveaways_contestant'])) {
            $contestant = $GLOBALS['ks_giveaways_contestant'];
        }

        if (null === $post) {
            $post = get_post();
        } else if (!is_object($post)) {
            $post = get_post($post);
        }

        if(!is_object($contestant)) {
            $contestant = KS_Contestant_DB::get($contestant, $post->ID);
        }

        if(!isset($contestant->first_name) or $contestant->first_name === "") {
            return ""; // Leave as empty in case of error
        };

        return $contestant->first_name;
    }

}