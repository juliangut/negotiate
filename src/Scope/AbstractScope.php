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
     * Header name.
     *
     * @var string
     */
    protected $header;

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
     * @param string             $header
     * @param string[]           $priorityList
     * @param AbstractNegotiator $negotiator
     * @param bool               $useDefaults
     */
    public function __construct(
        string $header,
        array $priorityList,
        AbstractNegotiator $negotiator,
        bool $useDefaults = true
    ) {
        $this->header = $header;
        $this->priorityList = $priorityList;
        $this->negotiator = $negotiator;
        $this->useDefaults = $useDefaults;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function getAccept(ServerRequestInterface $request): AcceptHeader
    {
        $accept = null;

        $header = $request->getHeaderLine($this->header);
        if ($header !== '') {
            $accept = $this->negotiator->getBest($header, $this->priorityList);
        }

        if ($accept === null) {
            if ($this->useDefaults) {
                return $this->getDefaultAccept();
            }

            throw new Exception(\sprintf('"%s" header refused', $this->header));
        }

        return $accept;
    }

    /**
     * Get default Accept header.
     *
     * @return AcceptHeader
     */
    abstract protected function getDefaultAccept(): AcceptHeader;
}
