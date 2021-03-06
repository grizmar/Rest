<?php

namespace Elantha\Api\Http\Exceptions;

use Elantha\Api\Exceptions\ApiException;
use Elantha\Api\Messages\KeeperInterface;
use Elantha\Api\Response\ResponseInterface;

abstract class BaseHttpException extends ApiException
{
    /* @var ResponseInterface $response */
    private $response;

    abstract public function getStatusCode(): int;

    public static function make($code = 0, array $context = []): self
    {
        /* @var KeeperInterface $keeper */
        $keeper = resolve(KeeperInterface::class);

        $message = $keeper->getMessage($code, $context);

        return new static($message, $code);
    }

    public function withoutMessage(): self
    {
        $this->message = '';

        return $this;
    }

    public function setResponse(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): ResponseInterface
    {
        if (empty($this->response)) {
            $this->response = resolve(ResponseInterface::class);
        }

        return $this->response;
    }
}
