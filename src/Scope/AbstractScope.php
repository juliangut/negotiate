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

namespace Jgut\Negotiate\Scope;

use Jgut\Negotiate\NegotiatorException;
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
        protected readonly AbstractNegotiator $negotiator,
        protected readonly bool $useDefaults = true,
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

    public function getAccept(ServerRequestInterface $request): BaseAccept
    {
        $accept = null;

        $header = $request->getHeaderLine($this->getHeaderName());
        if ($header !== '' && \count($this->priorityList) !== 0) {
            try {
                /** @var BaseAccept|null $accept */
                $accept = $this->negotiator->getBest($header, $this->priorityList);
                // @codeCoverageIgnoreStart
            } catch (NegotiateException) {
                // @ignoreException
            }
            // @codeCoverageIgnoreEnd
        }

        if ($accept === null) {
            if ($this->useDefaults) {
                return $this->getDefaultAccept();
            }

            throw new NegotiatorException(sprintf('"%s" header refused.', $this->getHeaderName()));
        }

        return $accept;
    }

    abstract public function getHeaderName(): string;

    abstract protected function getDefaultAccept(): BaseAccept;
}
