<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service for work with ZohoCRM API
 * Class ZohoServiceProvider
 * @package App\Providers
 */
class ZohoServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
    /**
     * Service method for make request to ZohoCRM API
     * @param $url
     * @param $param
     * @return mixed
     */
    private function request($url, $param, $jsonDecode = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = curl_exec($ch);
        curl_close($ch);

        return ($jsonDecode ? json_decode($result) : $result);
    }

    /**
     * Service method for generate auth token
     * @return mixed
     */
    public function getAuthToken()
    {
        $username = env('USER_EMAIL');
        $password = env('PASSWORD');
        $param = "SCOPE=ZohoCRM/crmapi&EMAIL_ID=" . $username . "&PASSWORD=" . $password;
        $url = 'https://accounts.zoho.com/apiauthtoken/nb/create';
        $result = $this->request($url, $param);
        $anArray = explode("\n", $result);
        $authToken = explode("=", $anArray['2']);

        return $authToken['1'];
    }

    /**
     * Service method for get contacts from ZohoCRM
     * @return array
     */
    public function getZohoContacts()
    {
        $response = $this->request(
            'https://crm.zoho.com/crm/private/json/Contacts/getRecords', [
            'authtoken' => empty(env('AUTHTOKEN')) ? $this->getAuthToken() : env('AUTHTOKEN'),
            'scope' => 'crmapi',
            'selectColumns' => 'Contacts(First Name,Last Name,Email,Phone)'
        ], true);

        // if no data - return empty array
        if (!$contactnsZoho = array_get($response, 'response.result.Contacts.row')) {
            return [];
        }
        $contacts = [];

        foreach ($contactnsZoho as $c) {
            $contacts[] = [
                'CONTACT_ID' => array_get($c, 'FL.0.content'),
                'first_name' => array_get($c, 'FL.1.content'),
                'last_name' => array_get($c, 'FL.2.content'),
                'email' => array_get($c, 'FL.3.content'),
                'phone' => array_get($c, 'FL.4.content'),
            ];
        }
        return $contacts;
    }

    /**
     * Service method for insert contact to ZohoCRM
     * @param array $contact
     * @return bool
     */
    public function insertContact(Array $contact)
    {
        //Make XML with contact info for ZohoCRM
        $newsXML = new \SimpleXMLElement("<Contacts></Contacts>");
        $row = $newsXML->addChild('row');
        $row->addAttribute('no', '1');
        $newsIntro = $row->addChild('FL', $contact['firstname']);
        $newsIntro->addAttribute('val', 'First Name');
        $newsIntro = $row->addChild('FL', $contact['lastname']);
        $newsIntro->addAttribute('val', 'Last Name');
        $newsIntro = $row->addChild('FL', $contact['email']);
        $newsIntro->addAttribute('val', 'Email');
        $newsIntro = $row->addChild('FL', $contact['phone']);
        $newsIntro->addAttribute('val', 'Phone');

        $result = $this->request(
            "https://crm.zoho.com/crm/private/xml/Contacts/insertRecords",
            [
                'authtoken' => empty(env('AUTHTOKEN')) ? $this->getAuthToken() : env('AUTHTOKEN'),
                'scope' => 'crmapi',
                'newFormat' => 1,
                'xmlData' => $newsXML->asXML()
            ]
        );

        $xml = new \SimpleXMLElement($result);
        return ($xml->result->message->__toString() == 'Record(s) added successfully');
    }
}
