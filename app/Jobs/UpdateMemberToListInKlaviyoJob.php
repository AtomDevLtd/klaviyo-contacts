<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\User;
use App\Services\Facades\KlaviyoConnection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateMemberToListInKlaviyoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Contact $contact
     */
    public Contact $contact;

    /**
     * @var User $user
     */
    public User $user;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
        $this->user    = $contact->contactList->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = config('project.klaviyo_base_url') .
            '/v1/person/' .
            $this->contact->klaviyo_id .
            '?api_key=' . $this->user->klaviyo_private_api_key .
            '&$email=' . $this->contact->email .
            '&$last_name=' . $this->contact->last_name .
            '&$first_name=' . $this->contact->first_name .
            '&$organization=' . $this->contact->organization .
            '&$title=' . $this->contact->title .
            '&$phone_number=' . $this->contact->phone;



            KlaviyoConnection::url($url)
                             ->header('Content-Type', 'application/json')
                             ->data($this->contact)
                             ->updateProfile();
    }
}
