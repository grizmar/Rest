<?php

namespace Elantha\Api\Messages;

use Elantha\Api\Exceptions\ApiException;

class Message
{
    private $code;
    private $text;

    public function __construct(string $code, string $text)
    {
        if (empty($text)) {
            throw new ApiException('Message text is not specified!');
        }

        $this->code = $code;
        $this->text = $text;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getText(array $context = []): string
    {
        $result = $this->text;

        if (!empty($context)) {
            $result = $this->replace($result, $context);
        }

        return $result;
    }

    private function replace(string $text, array $context): string
    {
        $search = [];
        $replacement = [];

        foreach ($context as $key => $value) {
            $search[] = ":$key";
            $replacement[] = $value;
        }

        return str_replace($search, $replacement, $text);
    }
}
