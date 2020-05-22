<?php


namespace MetForm\Utils;

use DateTime;

if (!defined('ABSPATH')) die('Forbidden');

/**
 * Ask client for rating and
 * other stuffs
 * Class Rating
 * @package MetForm\Utils
 */
class Rating
{
    private $plugin_name;
    private $days;
    private $style;
    private $rating_url;
    private $action;
    private $data;
    private $version;

    public function __construct($data)
    {

        // delete_option( $this->plugin_name.'_installDate' );

        if (current_user_can('update_plugins')) {

            // add_option($this->plugin_name . '_never_show','yes');

            $this->plugin_name = $data['name'] ?? 'plugin-name';
            $this->days = $data['days'] ?? 7;
            $this->style = $data['style'] ?? '';
            $this->rating_url = $data['url'] ?? 'http://site.com';
            $this->action = $data['action'];
            $this->data = $data;

            add_action('wp', [$this, 'cron_activation']);
            add_action($this->plugin_name . '_cronjob', [$this, 'corn_job_func']);
            $this->init();
        }
    }



    public function init()
    {


        if ($this->action_on_fire()) {
            if (get_option($this->plugin_name . '_ask_me_later') == 'yes' && get_option($this->plugin_name . '_never_show') != 'yes') {

                $this->ask_me_later();
            }

            if (get_option($this->plugin_name . '_never_show') != 'yes') {

                if (get_option($this->plugin_name . '_ask_me_later') == 'yes') {
                    return;
                }

                if (!$this->is_installation_date_exists()) {
                    $this->set_installation_date();
                }
                $this->is_used_in($this->data['days'] ?? 7);

                add_action('admin_footer', [$this, 'scripts'], 9999);
                add_action("wp_ajax_never_show_message", [$this, "never_show_message"]);
                add_action("wp_ajax_ask_me_later_message", [$this, "ask_me_later_message"]);
            }
        }
    }


    public function cron_activation()
    {
        if (!wp_next_scheduled($this->plugin_name . '_cronjob')) {
            wp_schedule_event(time(), 'daily', $this->plugin_name . '_cronjob');
        }
    }


    private function action_on_fire()
    {
        // var_dump(call_user_func($this->action));
        // die();
        if (call_user_func($this->action)) {
            return true;
        }
    }



    public function set_installation_date()
    {
        add_option($this->plugin_name . '_installDate', date('Y-m-d h:i:s'));
    }

    public function is_installation_date_exists()
    {

        return (get_option($this->plugin_name . '_installDate') == false) ? false : true;
    }


    public function get_installation_date()
    {

        return get_option($this->plugin_name . '_installDate');
    }

    public function set_first_action_date()
    {
        add_option($this->plugin_name . '_first_action_Date', date('Y-m-d h:i:s'));
        add_option($this->plugin_name . '_first_action', 'yes');
    }

    public function get_days($from_date, $to_date)
    {

        return round(($to_date->format('U') - $from_date->format('U')) / (60 * 60 * 24));
    }

    public function is_first_use($in_days)
    {

        $install_date = get_option($this->plugin_name . '_installDate');
        $display_date = date('Y-m-d h:i:s');
        $datetime1 = new DateTime($install_date);
        $datetime2 = new DateTime($display_date);
        $diff_interval = $this->get_days($datetime1, $datetime2);
        if ($diff_interval >= $in_days && get_option($this->plugin_name . '_first_action_Date') == "yes") {

            // action implementation here 

        }
    }





    public function is_used_in($days)
    {

        $install_date = get_option($this->plugin_name . '_installDate');
        $display_date = date('Y-m-d h:i:s');
        $datetime1 = new DateTime($install_date);
        $datetime2 = new DateTime($display_date);
        $diff_interval = $this->get_days($datetime1, $datetime2);

        $plugin_name = $this->plugin_name;




        if ($diff_interval >= $days) {

            //

            $array['btn'] = [
                [
                    'label' => 'Ok, you deserved it',
                    'url' => $this->rating_url,
                    'style' => [
                        'class' => 'none'
                    ],
                    'id' => 'btn_deserved'

                ],
                [
                    'label' => 'I already did',
                    'url' => '#',
                    'style' => [
                        'class' => 'none'
                    ],
                    'id' => 'btn_already_did'
                ],
                [
                    'label' => 'No, not good enough',
                    'style' => [
                        'class' => 'none'
                    ],
                    'url' => '#',
                    'id' => 'btn_not_good'
                ]
            ];

            $btn = $array['btn'];


            $con = get_option($this->plugin_name . '_never_show');

            Notice::push(
                [
                    'id' => $this->plugin_name . '_plugin_rating_msg_used_in_day',
                    'type' => 'info',
                    'dismissible' => false,
                    'btn' => $btn,
                    'style' => $this->style,
                    'message' => "Never show : {$con} Awesome, you've been using {$plugin_name}  for more {$diff_interval} days. May we ask you to give a 5-star rating on wordpress?",
                ]
            );
        }
    }


    /**
     * Change the status of Rating notification 
     * not to show the message again
     */


    public function never_show_message()
    {

        add_option($this->plugin_name . '_never_show', 'yes');
    }


    /**
     * 
     * Ask me later functionality
     * 
     */


    public function ask_me_later()
    {

        $days = 30;

        $install_date = get_option($this->plugin_name . '_installDate');
        $display_date = date('Y-m-d h:i:s');
        $datetime1 = new DateTime($install_date);
        $datetime2 = new DateTime($display_date);
        $diff_interval = $this->get_days($datetime1, $datetime2);

        $plugin_name = $this->plugin_name;




        if ($diff_interval >= $days) {

            //

            $array['btn'] = [
                [
                    'label' => 'Ok, you deserved it',
                    'url' => $this->rating_url,
                    'style' => [
                        'class' => 'none'
                    ],
                    'id' => 'btn_deserved'

                ],
                [
                    'label' => 'I already did',
                    'url' => '#',
                    'style' => [
                        'class' => 'none'
                    ],
                    'id' => 'btn_already_did'
                ],
                [
                    'label' => 'No, not good enough',
                    'style' => [
                        'class' => 'none'
                    ],
                    'url' => '#',
                    'id' => 'btn_not_good'
                ]
            ];

            $btn = $array['btn'];


            $con = get_option($this->plugin_name . '_never_show');

            Notice::push(
                [
                    'id' => $this->plugin_name . '_plugin_rating_msg_used_in_day',
                    'type' => 'info',
                    'dismissible' => false,
                    'btn' => $btn,
                    'style' => $this->style,
                    'message' => "Awesome, you've been using {$plugin_name}  for more {$diff_interval} days. May we ask you to give a 5-star rating on wordpress?",
                ]
            );
        }
    }


    /**
     * 
     * When user will click @notGoodEnough button
     * Then it will fire this function to change the status
     * for next asking time
     * 
     */


    public function ask_me_later_message()
    {

        if (get_option($this->plugin_name . '_ask_me_later') == false) {

            add_option($this->plugin_name . '_ask_me_later', 'yes');
        } else {

            add_option($this->plugin_name . '_never_show', 'yes');
        }
    }

    /**
     * 
     * Get current version of the plugin
     * 
     */

    public function get_current_version()
    {

        return $this->version;
    }

    /**
     * 
     * Get previous version of the plugin
     * that have been stored in database
     * 
     */


    public function get_previous_version()
    {

        return get_option($this->plugin_name . '_version');
    }

    /**
     * 
     *  Set current version of the plugin
     * 
     */

    public function set_version($version)
    {

        if (!get_option($this->plugin_name . '_version')) {

            add_option($this->plugin_name . '_version');
        } else {

            update_option($this->plugin_name . '_version', $version);
        }
    }

    /**
     * 
     * JS Ajax script for updating
     * rating status from users
     * 
     */

    public function scripts()
    {

        $dom_id = '';

        echo "
        <script>
        jQuery(document).ready(function ($) {
            $( '#btn_already_did' ).on( 'click', function() {
               
        
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action 	: 'never_show_message',
                        
                    },
                    success:function(response){
                        $('#metform-sites-notice-id-MetForm_plugin_rating_msg_used_in_day').remove();

                    }
                });
        
            });

            $('#btn_deserved').click(function(){
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action 	: 'never_show_message',
                        
                    },
                    success:function(response){
                        $('#metform-sites-notice-id-MetForm_plugin_rating_msg_used_in_day').remove();

                    }
                });
            });

            $('#btn_not_good').click(function(){
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action 	: 'ask_me_later_message',
                        
                    },
                    success:function(response){
                        $('#metform-sites-notice-id-MetForm_plugin_rating_msg_used_in_day').remove();

                    }
                });
            });
        
        });
        </script>
		";
    }

    /**
     * Cron job activities. Where it will check basic
     * functionality every day.
     * 
     */

    public function corn_job_func()
    {

        if ($this->get_current_version() != $this->get_previous_version()) {

            $this->set_version($this->get_current_version());
        }

        if ($this->action_on_fire()) {
            if (get_option($this->plugin_name . '_ask_me_later') == 'yes' && get_option($this->plugin_name . '_never_show') != 'yes') {

                $this->ask_me_later();
            }

            if (get_option($this->plugin_name . '_never_show') != 'yes') {

                if (get_option($this->plugin_name . '_ask_me_later') == 'yes') {
                    return;
                }

                if (!$this->is_installation_date_exists()) {
                    $this->set_installation_date();
                }
                $this->is_used_in($this->data['days'] ?? 7);

                add_action('admin_footer', [$this, 'scripts'], 9999);
                add_action("wp_ajax_never_show_message", [$this, "never_show_message"]);
                add_action("wp_ajax_ask_me_later_message", [$this, "ask_me_later_message"]);
            }
        }
    }
}
