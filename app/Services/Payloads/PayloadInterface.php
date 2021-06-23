<?php


namespace App\Services\Payloads;


interface PayloadInterface
{
    public function generate($model);
}
