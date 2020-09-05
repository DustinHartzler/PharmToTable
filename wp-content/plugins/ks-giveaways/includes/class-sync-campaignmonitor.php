<?php

if(!class_exists('KS_Giveaways_Vendor_CampaignMonitor'))
{
    require_once(dirname(__FILE__)."/vendor/campaignmonitor_api/KS_Giveaways_Vendor_CampaignMonitor.php");
}

class KS_Giveaways_CampaignMonitor
{
    /**
     * Instance of this class.
     */
    private static $instance;

    /**
     * Instance of the CampaignMonitor API connector
     *
     * @var $campaignMonitor KS_Giveaways_Vendor_CampaignMonitor
     */
    private $campaignMonitor;

    private $apiKey;

    /**
     * This method is private because the instance must always be attained by the singleton
     */
    private function __construct()
    {
        $this->apiKey = get_option(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY);

        $this->campaignMonitor = new KS_Giveaways_Vendor_CampaignMonitor($this->apiKey);

        if(!$this->campaignMonitor->ping())
        {
            $this->campaignMonitor = NULL;
        }
    }

    /**
     * Returns an instance of this class.
     *
     * @return KS_Giveaways_CampaignMonitor A single instance of this class.
     */
    public static function get_instance()
    {
        if(null == self::$instance)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Get an array of all Subscriber Lists
     *
     * @return array|bool
     */
    public function get_subscriber_lists()
    {
        if($this->campaignMonitor === NULL)
        {
            return false;
        }

        return $this->campaignMonitor->getSubscriberLists();
    }

    /**
     * Add subscriber to a subscriber list.
     *
     * @param string $listId
     * @param string $email
     * @return bool
     */
    public function add_subscriber($listId, $email, $first_name = null)
    {
        if($this->campaignMonitor === NULL)
        {
            return false;
        }

        return $this->campaignMonitor->addSubscriberToList($listId, $email, $first_name);
    }

    public function ping()
    {
        if($this->campaignMonitor)
        {
            return $this->campaignMonitor->ping();
        }

        return false;
    }

    public static function disconnect()
    {
        delete_option(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY);
    }

    public static function is_valid()
    {
        if(get_option(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY))
        {
            $cls = self::get_instance();

            return $cls->ping();
        }

        return false;
    }
}