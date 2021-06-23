<?php


namespace App\Services\Payloads;


class ContactListPayload implements PayloadInterface
{
    /**
     * @param $model
     * @return array
     */
    public function generate($model): array
    {
        return [
            'list_name' => $model->name,
        ];
    }
}
