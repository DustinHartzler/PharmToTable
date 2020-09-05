<?php

/**
 * Class KS_Giveaways_Vendor_CampaignMonitor
 */
class KS_Giveaways_Vendor_CampaignMonitor
{
    const API_URL = 'https://api.createsend.com/api/v3.1';

    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Gets a stdClass object of all Clients
     *
     * [
     *      "ClientID" => ""
     *      "Name" => ""
     * ]
     *
     * @return stdClass[]
     */
    public function getClients()
    {
        $result = $this->getURL(self::API_URL.'/clients.json');

        if($result['status'] !== 200)
        {
            return false;
        }

        return json_decode($result['body']);
    }

    /**
     * Returns an array of Subscriber Lists or false on failure
     *
     * return $lists = [
     *      "Client Name" => [
     *          "List ID" => "List Name",
     *          "List ID" => "List Name"
     *      ],
     *      "Client Name" => [
     *          "List ID" => "List Name",
     *          "List ID" => "List Name"
     *      ]
     * ]
     *
     *
     * @return array|bool
     */
    public function getSubscriberLists()
    {
        if(!($clients = $this->getClients()))
        {
            return false;
        }

        $lists = array();

        foreach($clients as $client)
        {
            $lists[$client->Name] = array();

            $result = $this->getURL(self::API_URL."/clients/{$client->ClientID}/lists.json");

            if($result['status'] !== 200)
            {
                return false;
            }

            $client_lists = json_decode($result['body']);

            foreach($client_lists as $client_list)
            {
                $lists[$client->Name][$client_list->ListID] = $client_list->Name;
            }
        }

        return $lists;
    }

    /**
     * Add a email subscriber to a given list.
     *
     * @param string $subscriberListId
     * @param string $email
     * @param string $name
     * @return bool
     */
    public function addSubscriberToList($subscriberListId, $email, $name = null)
    {
        $result = $this->postURL(self::API_URL."/subscribers/{$subscriberListId}.json", json_encode(array(
            'EmailAddress'  => $email,
            'Name'          => $name
        )));

        if($result['status'] !== 201)
        {
            $errors = array(
                1 => 'Email Address passed in was invalid',
                204 => 'Email Address has existed in the selected list before, and currently exists in suppression list',
                205 => 'Email Address exists in deleted list',
                206 => 'Email Address exists in unsubscribed list',
                207 => 'Email Address exists in bounced list',
                208 => 'Email Address exists in unconfirmed list'
            );

            return isset($errors[$result['status']]) ? $errors[$result['status']] : false;
        }

        return true;
    }

    /**
     * Ping the API server to verify the API key
     *
     * @return bool
     */
    public function ping()
    {
        return ($this->getClients() == true);
    }

    /**
     * GETs an URL using cURL
     *
     * @param $page
     * @param integer $timeout
     *
     * @return bool|mixed
     */
    private function getURL($page, $timeout = 3)
    {
        $ch = curl_init($page);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int) $timeout);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->apiKey}:password");
        $ret = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array(
            'status' => $status,
            'body' => $ret
        );
    }

    /**
     * POSTs data to an URL
     *
     * @param $page
     * @param array|string $args
     * @param integer $timeout
     *
     * @return bool|mixed
     */
    private function postURL($page, $args, $timeout = 3)
    {
        $ch = curl_init($page);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int) $timeout);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->apiKey}:password");
        curl_setopt($ch, CURLOPT_HEADER, "Content-type: application/json");
        $ret = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array(
            'status' => $status,
            'body' => $ret
        );
    }
}
