<?php
if(!class_exists('KS_Giveaways_Vendor_ConvertKitAPI'))
{
    require_once(dirname(__FILE__)."/vendor/convertkit_api/ConvertKitAPI.php");
}

class KS_Giveaways_ConvertKit
{
    /**
     * Instance of this class.
     */
    private static $instance;

    /**
     * Instance of the ConvertKit API connector
     *
     * @var $convertkit KS_Giveaways_Vendor_ConvertKitAPI
     */
    private $convertKit;

    private $apiKey;


	function __construct()
	{
		$this->apiKey = get_option(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY);

        $this->convertKit = new KS_Giveaways_Vendor_ConvertKitAPI($this->apiKey);

        if( ! $this->convertKit->ping())
        {
            $this->convertKit = NULL;
        }
	}


    public function get_forms()
    {
        if($this->convertKit === NULL)
        {
            return false;
        }

        return $this->convertKit->getForms();
    }


    /**
     * Add subscriber to a subscriber list.
     *
     * @param string $listId
     * @param string $email
     * @return bool
     */
    public function add_subscriber($formId, $email, $firstName = null)
    {
        if($this->convertKit === NULL)
        {
            return false;
        }

        return $this->convertKit->addSubscriberToForm($formId, $email, $firstName);
    }


    public function ping()
    {
        if ($this->convertKit) {
            return $this->convertKit->ping();
        }

        return FALSE;
    }


    public static function disconnect()
    {
        delete_option(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY);
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


	public static function is_valid()
    {
        if(get_option(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY))
        {
            $cls = self::get_instance();
            return $cls->ping();
        }

        return false;
    }
}