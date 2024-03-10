<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Scope;

use Jgut\Negotiate\NegotiatorException;
use Jgut\Negotiate\Provider;
use Negotiation\AbstractNegotiator;
use Negotiation\BaseAccept;
use Negotiation\Exception\Exception as NegotiateException;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractScope implements ScopeInterface
{
    public function __construct(
        /**
         * @var list<string> $priorityList
         */
        protected array $priorityList,
        protected ?string $default = null,
    ) {}

    /**
     * Prepend priority on top of priority list.
     */
    public function prependPriority(string $priority): void
    {
        array_unshift($this->priorityList, $priority);
    }

    public function appendPriority(string $priority): void
    {
        $this->priorityList[] = $priority;
    }

    public function setDefault(?string $default): void
    {
        $this->default = $default;
    }

    public function negotiateRequest(ServerRequestInterface $request, string $attributeName): ServerRequestInterface
    {
        $negotiationResult = $this->getAccept($request);

        /** @var Provider|null $negotiationProvider */
        $negotiationProvider = $request->getAttribute($attributeName);
        $negotiationProvider = ($negotiationProvider ?? new Provider([]))
            ->withAccept($this->getHeaderName(), $negotiationResult);

        return $request
            ->withHeader($this->getHeaderName(), $negotiationResult->getValue())
            ->withAttribute($attributeName, $negotiationProvider);
    }

    /**
     * @throws NegotiatorException
     */
    protected function getAccept(ServerRequestInterface $request): BaseAccept
    {
        $header = $request->getHeaderLine($this->getHeaderName());

        $accept = null;
        if ($header !== '' && \count($this->priorityList) !== 0) {
            try {
                /** @var BaseAccept|null $accept */
                $accept = $this->getNegotiator()
                    ->getBest($header, $this->priorityList);
                // @codeCoverageIgnoreStart
            } catch (NegotiateException) {
                // @ignoreException
            }
            // @codeCoverageIgnoreEnd
        }

        if ($accept === null) {
            $default = $this->getDefaultAccept();
            if ($default !== null) {
                return $default;
            }

            throw new NegotiatorException(sprintf('"%s" header missing or refused.', $this->getHeaderName()));
        }

        return $accept;
    }

    abstract public function getHeaderName(): string;

    abstract protected function getNegotiator(): AbstractNegotiator;

    abstract protected function getDefaultAccept(): ?BaseAccept;
}
