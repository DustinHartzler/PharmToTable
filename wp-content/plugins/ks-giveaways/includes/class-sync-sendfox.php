<?php
if(!class_exists('SendFox'))
{
    require_once(dirname(__FILE__)."/vendor/sendfox_api/SendFox.php");
}

class KS_Giveaways_SendFox
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

    private $apiToken;


	function __construct()
	{
		$this->apiToken = get_option(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN);

        $this->sendfox = new SendFox($this->apiToken);

        if( ! $this->ping())
        {
            $this->sendfox = NULL;
        }
	}


    public function get_tags()
    {
        try {
            if (!$this->sendfox)
                throw new Exception('SendFox not connected');

            $lists = array();

            if ($tags = $this->sendfox->getTags()) {
                foreach ($tags->data as $tag) {
                    $lists[$tag->id] = $tag->name;
                }
            }

            return $lists;
        }

        catch(Exception $e) {
        }

        return false;
    }


    /**
     * Add subscriber to a subscriber list.
     *
     * @param string $listId
     * @param string $email
     * @return bool
     */
    public function add_subscriber($tagId, $email, $firstName = null)
    {
        try {
            if (!$this->sendfox) {
                throw new Exception('SendFox not connected');
            }

            /*
            if ($first_name) {
                $sub_data['merge_fields'] = array('FNAME' => $first_name);
            }
            */

            $result = $this->sendfox->addContact($email, $firstName, $tagId);
            return true;
        }

        catch(Exception $e) {
            return $e->getMessage();
        }

        return false;
    }


    public function ping()
    {
        if($this->sendfox && $this->sendfox->validateToken()){
            return true;
        }

        return false;
    }


    public static function disconnect()
    {
        delete_option(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN);
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
        if(get_option(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN))
        {
            $cls = self::get_instance();
            return $cls->ping();
        }

        return false;
    }
}