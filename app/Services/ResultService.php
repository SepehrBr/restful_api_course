<?php
namespace App\Services;

class ResultService
{
    /**
     * Summary of __construct
     * @param bool $ok
     * @param mixed $data
     */
    public function __construct(public bool $ok, public mixed $data) {}
}
