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

use Jgut\Negotiate\Exception;
use Negotiation\AbstractNegotiator;
use Negotiation\AcceptHeader;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstract scope.
 */
abstract class AbstractScope implements ScopeInterface
{
    /**
     * List of negotiation priorities.
     *
     * @var string[]
     */
    protected $priorityList;

    /**
     * Negotiator.
     *
     * @var AbstractNegotiator
     */
    protected $negotiator;

    /**
     * Use defaults.
     *
     * @var bool
     */
    protected $useDefaults;

    /**
     * AbstractScope constructor.
     *
     * @param string[]           $priorityList
     * @param AbstractNegotiator $negotiator
     * @param bool               $useDefaults
     */
    public function __construct(array $priorityList, AbstractNegotiator $negotiator, bool $useDefaults = true)
    {
        $this->priorityList = $priorityList;
        $this->negotiator = $negotiator;
        $this->useDefaults = $useDefaults;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccept(ServerRequestInterface $request): AcceptHeader
    {
        $accept = null;

        $header = $request->getHeaderLine($this->getHeaderName());
        if ($header !== '') {
            $accept = $this->negotiator->getBest($header, $this->priorityList);
        }

        if ($accept === null) {
            if ($this->useDefaults) {
                return $this->getDefaultAccept();
            }

            throw new Exception(\sprintf('"%s" header refused', $this->getHeaderName()));
        }

        return $accept;
    }

    /**
     * Get handled header name.
     *
     * @return string
     */
    abstract public function getHeaderName(): string;

    /**
     * {@inheritdoc}
     */
    abstract protected function getDefaultAccept(): AcceptHeader;
}
