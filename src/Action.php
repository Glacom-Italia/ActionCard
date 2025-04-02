<?php

declare(strict_types=1);

namespace Glacom\ActionCard;

use JsonSerializable;

class Action implements JsonSerializable
{
    public function __construct(
        public string $label,
        public string $url,
        public string $method = 'GET',
        public array $headers = [],
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'label' => $this->label,
            'url' => $this->url,
            'method' => $this->method,
            'headers' => $this->headers,
        ];
    }
}
