<?php
namespace App\Http\Controllers\v1;

use App\Contact;
use CristianPontes\ZohoCRMClient\ZohoCRMClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * This is the first version for work with ZohoCRM Api. In this version using ZohoCRMClient library.
 * Class MainController
 * @package App\Http\Controllers\v1
 */
class MainController extends Controller
{
    /**
     * method for display create contact form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getInsertContacts()
    {
        return view('v1.create');
    }

    /**
     * method send request for create contact in ZohoCRM
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postInsertContacts(Request $request)
    {
        $client = new ZohoCRMClient('Contacts', env('AUTHTOKEN'));
        $this->validate($request, [
            'first' => 'required',
            'last' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        // Insert data in ZohoCRM
        $client->insertRecords()
            ->setRecords([
                array(
                    'First Name' => $request->first,
                    'Last Name' => $request->last,
                    'Email' => $request->email,
                    'Phone' => $request->phone,
                )
            ])
            ->onDuplicateError()
            ->triggerWorkflow()
            ->request();

        return  redirect()->action('v1\MainController@getInsertContacts');
    }

    /**
     * method for get contact from ZohoCrm and save them in database
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getZohoContacts()
    {
        $client = new ZohoCRMClient('Contacts', env('AUTHTOKEN'));

        //Get record from ZohoCRm
        $records = $client->getRecords()
                        ->selectColumns('First Name', 'Last Name', 'Email', 'Phone')
                        ->sortBy('Last Name')->sortAsc()
                        ->request();

        foreach ($records as $record) {
            $data = [
                'CONTACT_ID' => $record->data['CONTACTID'],
                'first_name' => $record->data['First Name'],
                'last_name' => $record->data['Last Name'],
                'email' => $record->data['Email'],
                'phone' => $record->data['Phone'],
            ];

            if(!$contact = Contact::where('CONTACT_ID', $record->data['CONTACTID'])->first()){
                Contact::create($data);
            }else{
                $contact->CONTACT_ID = $data['CONTACT_ID'];
                $contact->first_name = $data['first_name'];
                $contact->last_name = $data['last_name'];
                $contact->email = $data['email'];
                $contact->phone = $data['phone'];
            }
        }

        return redirect()->action('v1\MainController@getContacts');
    }

    /**
     * method for get all contacts from database
     * @return $this
     */
    public function getContacts()
    {
        return view('v1.contacts')->with(['contacts' => Contact::all()]);
    }

}
