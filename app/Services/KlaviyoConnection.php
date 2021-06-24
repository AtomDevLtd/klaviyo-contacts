<?php


namespace App\Services;


use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class KlaviyoConnection
{
    protected string $url;

    protected object $data;

    protected array $headers;

    protected $response;


    public function __construct()
    {
        $this->headers = [];
    }

    /**
     * Set a new ExternalPlatformConnection url.
     *
     * @param  $url
     * @return ExternalPlatformConnection
     * @throws \Throwable
     */
    public function url($url): self
    {
        throw_if(
            is_null($url),
            ValidationException::withMessages([
                'job_error' => 'No url is set for the request!',
            ])
        );

        $this->url = $url;
        return $this;
    }

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function data(object $model): self
    {
        $this->data =  $model;

        return $this;
    }

    public function createList(): self
    {
        $response =  Http::withHeaders($this->headers)
                         ->asForm()
                         ->post($this->url, [
                             'list_name' => $this->data->name,
                         ]);

        $this->response = $response->json();

        return $this;
    }

    public function updateList(): self
    {
        $response =  Http::withHeaders($this->headers)
                         ->asForm()
                         ->put($this->url, [
                            'list_name' => $this->data->name,
                            'api_key'   => config('project.klaviyo_account_key')
                         ]);

        $this->response = $response->json();

        $this->syncKlaviyoDatetime();

        return $this;
    }



    public function addMember(): self
    {
        $response =  Http::withHeaders($this->headers)
                         ->asJson()
                         ->post($this->url, [
                             'profiles' => [
                                 [
                                     'first_name'   => $this->data->first_name,
                                     'last_name'    => $this->data->last_name,
                                     'email'        => $this->data->email,
                                     'title'        => $this->data->title,
                                     'organization' => $this->data->organization,
                                     'phone_number' => $this->data->phone
                                 ]
                             ]
                         ]);

        $this->response = $response->json();

        return $this;
    }


    public function updateProfile(): self
    {
        $response =  Http::put($this->url, []);

        $this->response = $response->json();

        $this->syncKlaviyoDatetime();

        return $this;
    }

    public function saveKlaviyoListId(): void
    {
        $this->data->update([
            'klaviyo_id'            => $this->response['list_id'],
            'klaviyo_sync_datetime' => now()
        ]);
    }

    public function  saveKlaviyoProfileId(): void
    {
        $this->data->update([
            'klaviyo_id'            => $this->response[0]['id'],
            'klaviyo_sync_datetime' => now()
        ]);
    }

    private function syncKlaviyoDatetime(): void
    {
        $this->data->update([
            'klaviyo_sync_datetime' => now()
        ]);
    }
}
