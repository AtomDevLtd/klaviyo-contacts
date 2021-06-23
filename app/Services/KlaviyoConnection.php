<?php


namespace App\Services;


use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class KlaviyoConnection
{
    protected string $url;

    protected array $data;

    protected array $headers;

    protected PayloadFactory $payloadFactory;

    protected object $instance;

    protected $response;


    public function __construct()
    {
        $this->payloadFactory = new PayloadFactory;
        $this->headers = [];
        $this->data = [];
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

    public function data(object $instance): self
    {
        $this->instance = $instance;

        $this->data =  $this->payloadFactory
                            ->initializePayload(get_class($instance))
                            ->generate($instance);

        return $this;
    }

    public function customParams($field, $value): self
    {
        $this->data[$field] = $value;

        return $this;
    }


    public function post(): self
    {
        $response =  Http::withHeaders($this->headers)->asForm()->post($this->url, $this->data);

        $this->response = $response->json();

        return $this;
    }

    public function put(): self
    {
        $response =  Http::withHeaders($this->headers)->asForm()->put($this->url, $this->data);

        $this->response = $response->json();

        return $this;
    }

    public function updateModel($field, $responseKey)
    {
        $this->instance->update([
            $field => $this->response[$responseKey]
        ]);
    }
}
