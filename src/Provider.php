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
class Provider
{
    /**
     * Accept list.
     *
     * @var AcceptHeader[]|\Negotiation\BaseAccept[]
     */
    protected $acceptList = [];

    /**
     * Add accept header.
     *
     * @param string       $name
     * @param AcceptHeader $accept
     */
    public function addAccept(string $name, AcceptHeader $accept): void
    {
        $this->acceptList[\ucfirst($name)] = $accept;
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
        return $this->acceptList[\ucfirst($name)] ?? null;
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
            $getValue = \substr($match[1], -4) === 'Line';

            $accept = $this->get($getValue ? \substr($match[1], 0, -4) : $match[1]);
            if ($accept !== null && $getValue) {
                $accept = $accept->getValue();
            }

            return $accept;
        }

        return null;
    }
}
