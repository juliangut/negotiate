<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate;

use Negotiation\AcceptHeader;
use Negotiation\BaseAccept;

final class Provider
{
    /**
     * @var array<string, BaseAccept>
     */
    private array $negotiated = [];

    /**
     * @param array<string, BaseAccept> $negotiated
     */
    public function __construct(array $negotiated)
    {
        foreach ($negotiated as $name => $accept) {
            $this->addAccept($name, $accept);
        }
    }

    private function addAccept(string $name, BaseAccept $accept): void
    {
        $this->negotiated[ucfirst($name)] = $accept;
    }

    public function withAccept(string $name, BaseAccept $accept): self
    {
        $negotiated = $this->negotiated;
        $negotiated[$name] = $accept;

        return new self($negotiated);
    }

    public function get(string $name): ?BaseAccept
    {
        return $this->negotiated[ucfirst($name)] ?? null;
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): AcceptHeader|BaseAccept|string|null
    {
        $accept = null;

        if (preg_match('/^get(?P<accept>.+)$/', $name, $matches) === 1) {
            $name = ucfirst($matches['accept']);
            $getValue = mb_substr($name, -4) === 'Line';

            $accept = $this->get($getValue ? mb_substr($name, 0, -4) : $name);
            if ($accept !== null && $getValue) {
                $accept = $accept->getValue();
            }
        }

        return $accept;
    }
}
