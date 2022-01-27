<?php

/*
 * negotiate (https://github.com/juliangut/negotiate).
 * Negotiation middleware.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Jgut\Negotiate;

use Negotiation\AcceptHeader;

final class Provider
{
    /**
     * @var array<AcceptHeader>|array<\Negotiation\BaseAccept>
     */
    private array $negotiated = [];

    /**
     * @param array<string, AcceptHeader> $negotiated
     */
    public function __construct(array $negotiated)
    {
        foreach ($negotiated as $name => $accept) {
            $this->addAccept($name, $accept);
        }
    }

    /**
     * Add accept header.
     */
    private function addAccept(string $name, AcceptHeader $accept): void
    {
        $this->negotiated[ucfirst($name)] = $accept;
    }

    /**
     * Get accept.
     *
     * @return AcceptHeader|\Negotiation\BaseAccept|null
     */
    public function get(string $name)
    {
        return $this->negotiated[ucfirst($name)] ?? null;
    }

    /**
     * @param array<mixed> $arguments
     *
     * @return AcceptHeader|\Negotiation\BaseAccept|string|null
     */
    public function __call(string $name, array $arguments)
    {
        if (preg_match('/^get(.+)$/', $name, $match) === 1) {
            $name = ucfirst($match[1]);
            $getValue = mb_substr($name, -4) === 'Line';

            $accept = $this->get($getValue ? mb_substr($name, 0, -4) : $name);
            if ($accept !== null && $getValue) {
                $accept = $accept->getValue();
            }

            return $accept;
        }
    }
}
