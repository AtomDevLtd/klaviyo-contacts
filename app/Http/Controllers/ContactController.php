<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Imports\ContactsImport;
use App\Jobs\AddMemberToListInKlaviyoJob;
use App\Jobs\UpdateMemberToListInKlaviyoJob;
use App\Models\Contact;
use App\Models\ContactList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(ContactList $contactList)
    {
        $this->authorize('viewAny', [Contact::class, $contactList]);

        $contacts = Contact::forContactList($contactList->id)->get();

        return view('pages.contacts.index', compact('contacts','contactList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(ContactList $contactList)
    {
        $this->authorize('create', [Contact::class, $contactList]);

        return view('pages.contacts.create', compact('contactList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateContactRequest $request
     * @param ContactList $contactList
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(CreateContactRequest $request, ContactList $contactList)
    {
       $validated = $request->validated();

       $contact = $contactList->contacts()->create($validated);

       AddMemberToListInKlaviyoJob::dispatchIf(
           $request->user()->hasKlaviyoApiKeys() && $contactList->isInKlaviyo(),
           $contact)
                                  ->onQueue('contact');

       return redirect()->route('contactLists.contacts.index', $contactList)->with('message', 'Contact saved successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(ContactList $contactList, Contact $contact)
    {
        $this->authorize('view', $contact);

        return view('pages.contacts.edit', compact('contactList', 'contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateContactRequest  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateContactRequest $request, ContactList $contactList, Contact $contact)
    {
        $this->authorize('update', $contact);

        $validated = $request->validated();

        $contact = tap($contact)->update($validated);

        UpdateMemberToListInKlaviyoJob::dispatchIf(
            $request->user()->hasKlaviyoApiKeys() && $contactList->isInKlaviyo(),
            $contact)
                                      ->onQueue('contact');

        return redirect()->route('contactLists.contacts.index', $contactList)->with('message', 'Contact updated successfully');
    }

    /**
     * @param Request $request
     * @param ContactList $contactList
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request, ContactList $contactList): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $import = new ContactsImport($contactList);

        Excel::import($import, request()->file('file'));

        return redirect()->route('contactLists.contacts.index', $contactList)->with('message', 'Contacts saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function syncWithKlaviyo(Request $request, Contact $contact): Response
    {
        if(!$contact->klaviyo_id){
           AddMemberToListInKlaviyoJob::dispatchIf(
                    $request->user()->hasKlaviyoApiKeys() && $contact->contactList->isInKlaviyo(),
                    $contact)
                                       ->onQueue('contact');
        }

        return response([
            'data' => $contact
        ], Response::HTTP_OK);
    }
}
