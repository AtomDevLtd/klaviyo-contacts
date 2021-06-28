<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactListRequest;
use App\Http\Requests\UpdateContactListRequest;
use App\Jobs\CreateListInKlaviyoJob;
use App\Jobs\UpdateListInKlaviyoJob;
use App\Models\ContactList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $contactLists = ContactList::forUser($request->user()->getKey())->get();

        return view('pages.contact-lists.index', compact('contactLists'));
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('pages.contact-lists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateContactListRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateContactListRequest $request)
    {
        $validated = $request->validated();

        $contactList = ContactList::create([
            'name'        => $validated['name'],
            'user_id'     => $request->user()->getKey()
        ]);

        CreateListInKlaviyoJob::dispatchIf($request->user()->hasKlaviyoApiKeys(), $contactList)
                              ->onQueue('contact-list');

        return redirect()->route('contactLists.index')->with('message', 'Contact List saved successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactList  $contactList
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(ContactList $contactList)
    {
        $this->authorize('view', $contactList);

        return view('pages.contact-lists.edit', compact('contactList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateContactListRequest  $request
     * @param  \App\Models\ContactList  $contactList
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateContactListRequest $request, ContactList $contactList)
    {
        $this->authorize('update', $contactList);

        $validated = $request->validated();

        $contactList = tap($contactList)->update([
            'name' => $validated['name']
        ]);

        UpdateListInKlaviyoJob::dispatchIf($request->user()->hasKlaviyoApiKeys(), $contactList)
                              ->onQueue('contact-list');

        return redirect()->route('contactLists.index')->with('message', 'Contact List updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\ContactList $contactList
     * @return \Illuminate\Http\Response
     */
    public function syncWithKlaviyo(Request $request, ContactList $contactList): Response
    {
        if(!$contactList->isInKlaviyo()){
            CreateListInKlaviyoJob::dispatchIf(
                $request->user()->hasKlaviyoApiKeys(),
                $contactList
            )
                ->onQueue('contact-list');
        }

        return response([
            'data' => $contactList
        ], Response::HTTP_OK);
    }

}
