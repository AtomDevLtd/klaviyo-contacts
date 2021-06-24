<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Jobs\AddMemberToListInKlaviyoJob;
use App\Jobs\UpdateMemberToListInKlaviyoJob;
use App\Models\Contact;
use App\Models\ContactList;

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

       AddMemberToListInKlaviyoJob::dispatch($contact);

       return redirect()->route('contactLists.contacts.index', $contactList);
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

        UpdateMemberToListInKlaviyoJob::dispatch($contact);

        return redirect()->route('contactLists.contacts.index', $contactList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
