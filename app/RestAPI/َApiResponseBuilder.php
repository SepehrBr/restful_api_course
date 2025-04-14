<?php
namespace App\RestAPI;

class َApiResponseBuilder
{
    private ApiResponse $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponse();
    }

    /**
    * Chained method for setting success in the API response.
     * @param bool $success
     * @return static
     */
    public function withSuccess(bool $success)
    {
        $this->apiResponse->setSuccess($success);

        return $this;
    }

    /**
    * Chained method for setting a message in the API response.
     * @param string $message
     * @return static
     */
    public function withMessage(?string $message)
    {
        $this->apiResponse->setMessage($message);

        return $this;
    }

    /**
    * Chained method for setting a data in the API response.
     * @param mixed $data
     * @return static
     */
    public function withData(mixed $data)
    {
        $this->apiResponse->setData($data);

        return $this;
    }

    /**
    * Chained method for setting a status in the API response.
     * @param int $status
     * @return static
     */
    public function withStatus(int $status)
    {
        $this->apiResponse->setStatus($status);

        return $this;
    }

    /**
     * @param string|array $appends
     */
    public function withAppends(string|array $appends)
    {
        $this->apiResponse->setAppends($appends);

        return $this;
    }

    public function build()
    {
        return $this->apiResponse->response();
    }
}
