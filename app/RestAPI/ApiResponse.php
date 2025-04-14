<?php
namespace App\RestAPI;

use phpDocumentor\Reflection\Types\Boolean;

class ApiResponse
{
    private bool $success = true;
    private ?string $message = null;
    private mixed $data = null;
    private int $status = 200;
    private array $appends = [];

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data)
    {
        $this->data = $data;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @param string|array $appends
     */
    public function setAppends(string|array $appends)
    {
        $this->appends = $appends;
    }

    public function response()
    {
        $body = [];

        ! is_null($this->success) && $body['success'] = $this->success;
        ! is_null($this->message) && $body['message'] = $this->message;
        ! is_null($this->data) && $body['data'] = $this->data;
        $body += $this->appends;

        return response()->json($body, $this->status);
    }
}
