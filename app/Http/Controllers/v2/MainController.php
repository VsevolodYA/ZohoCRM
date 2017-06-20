<?php
namespace App\Http\Controllers\v2;

use App\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\ZohoServiceProvider;
use Illuminate\Support\Facades\App;

/**
 * This is the second version for work with ZohoCRM Api. In this version using.
 * Class MainController
 * @package App\Http\Controllers\v2
 */
class MainController extends Controller
{
    private $zoho;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function __construct()
    {
        $this->zoho = new ZohoServiceProvider(App::class);
    }

    /**
     * method for display create contact form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getInsertContacts()
    {
        return view('v2.create');
    }

    /**
     * method get data from form and send to insertContact service method
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postInsertContacts(Request $request)
    {
        $this->validate($request, [
            'first' => 'required',
            'last' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $contact = [
            'firstname' => $request->first,
            'lastname' => $request->last,
            'email' => $request->email,
            'phone' => $request->phone
        ];

        $this->zoho->insertContact($contact);

        return  redirect()->action('v2\MainController@getInsertContacts');
    }

    /**
     * method for get contact from ZohoCrm and save them in database
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getZohoContacts()
    {
        $response = $this->zoho->getZohoContacts();
        foreach ($response as $record) {

            $contact = [
                'CONTACT_ID' => $record['CONTACT_ID'],
                'first_name' => $record['first_name'],
                'last_name' => $record['last_name'],
                'email' => $record['email'],
                'phone' => $record['phone'],
            ];

            Contact::where('CONTACT_ID', $contact['CONTACT_ID'])->updateOrCreate($contact);
        }
        return redirect()->action('v2\MainController@getContacts');
    }

    /**
     * method for get all contacts from database
     * @return $this
     */
    public function getContacts()
    {
        return view('v2.contacts')->with(['contacts' => Contact::all()]);
    }

}
