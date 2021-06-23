<?php

namespace App\Jobs;

use App\Models\ContactList;
use App\Services\Facades\KlaviyoConnection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateContactListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ContactList $contactList
     */
    public ContactList $contactList;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContactList $contactList)
    {
        $this->contactList = $contactList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        KlaviyoConnection::url('https://a.klaviyo.com/api/v2/list/' . $this->contactList->klaviyo_id)
                         ->header('Content-Type', 'application/x-www-form-urlencoded')
                         ->data($this->contactList)
                         ->customParams('api_key', config('project.klaviyo_account_key'))
                         ->put();
    }
}
