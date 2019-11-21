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

/**
 * Negotiation provider.
 */
final class Provider
{
    /**
     * Negotiated list.
     *
     * @var AcceptHeader[]|\Negotiation\BaseAccept[]
     */
    private $negotiated = [];

    /**
     * Provider constructor.
     *
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
     *
     * @param string       $name
     * @param AcceptHeader $accept
     */
    private function addAccept(string $name, AcceptHeader $accept): void
    {
        $this->negotiated[\ucfirst($name)] = $accept;
    }

    /**
     * Get accept.
     *
     * @param string $name
     *
     * @return AcceptHeader|\Negotiation\BaseAccept|null
     */
    public function get(string $name)
    {
        return $this->negotiated[\ucfirst($name)] ?? null;
    }

    /**
     * @param string  $name
     * @param mixed[] $arguments
     *
     * @return AcceptHeader|\Negotiation\BaseAccept|string|null
     */
    public function __call(string $name, array $arguments)
    {
        if (\preg_match('/^get(.+)$/', $name, $match) === 1) {
            $name = \ucfirst($match[1]);
            $getValue = \substr($name, -4) === 'Line';

            $accept = $this->get($getValue ? \substr($name, 0, -4) : $name);
            if ($accept !== null && $getValue) {
                $accept = $accept->getValue();
            }

            return $accept;
        }

        return null;
    }
}
