<?php

namespace App\Jobs;

use App\Models\ContactList;
use App\Models\User;
use App\Services\Facades\KlaviyoConnection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateListInKlaviyoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ContactList $contactList
     */
    public ContactList $contactList;

    /**
     * @var User $user
     */
    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContactList $contactList)
    {
        $this->contactList = $contactList;
        $this->user = $contactList->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        KlaviyoConnection::url(config('project.klaviyo_base_url') . '/v2/lists?api_key=' . $this->user->klaviyo_private_api_key)
                         ->header('Content-Type', 'application/x-www-form-urlencoded')
                         ->data($this->contactList)
                         ->createList()
                         ->saveKlaviyoListId();
    }
}
