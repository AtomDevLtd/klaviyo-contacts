<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TrackActivityController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $payload = [
            'token' => $request->user()->klaviyo_public_api_key,
            'event' => 'Clicked Button',
            'customer_properties' => [
                '$email' => $request->user()->email
            ]
        ];

        $response = Http::get(
            config('project.klaviyo_base_url') . '/track?data=' . base64_encode(json_encode($payload)),
        );

        if(!$response->body()){
            return redirect()->route('contactLists.index')
                             ->withErrors(['Please check your KLAVIYO API keys']);
        }

        return redirect()->route('contactLists.index')
                         ->with('message', 'Information about that activity was sent to your account in Klaviyo.
                                            The activity will be shown after some time in a metric with name - Clicked Button');
    }
}
