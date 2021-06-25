<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\ContactList;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    /**
     * @var ContactList $contactList
     */
    public ContactList $contactList;

    public function __construct(ContactList $contactList)
    {
        $this->contactList = $contactList;
    }

    public function model(array $row)
    {
        return new Contact([
            'email'        => $row['email'],
            'first_name'   => $row['first_name'] ?? null ,
            'last_name'    => $row['last_name'] ?? null,
            'title'        => $row['title'] ?? null,
            'organization' => $row['organization'] ?? null,
            'phone'        => $row['phone'] ?? null,
            'contact_list_id' => $this->contactList->id
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'last_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
            ],
            'title' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'organization' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                'phone:US,BE,BG,PL'
            ],
            'contact_list_id' => Rule::exists('contact-lists', 'id')
        ];
    }
}
