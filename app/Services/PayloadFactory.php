<?php


namespace App\Services;

use App\Services\Payloads\ContactListPayload;
use App\Services\Payloads\ContactPayload;

class PayloadFactory
{
    public function initializePayload($model)
    {
        $payloadClass = [
            'App\Models\ContactList'  => ContactListPayload::class,
            'App\Models\Contact'      => ContactPayload::class
            ][$model];

        return new $payloadClass();

    }
}
