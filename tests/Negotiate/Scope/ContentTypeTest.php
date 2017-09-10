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

namespace Jgut\Negotiate\Tests\Scope;

use Jgut\Negotiate\Scope\ContentType;
use Negotiation\Accept;
use Negotiation\Negotiator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Content Type scope tests.
 */
class ContentTypeTest extends TestCase
{
    public function testDefaultAccept()
    {
        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects($this->once())
            ->method('getBest')
            ->will($this->returnValue(null));
        /* @var Negotiator $negotiator */

        $scope = new ContentType(['test/html'], $negotiator, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getHeaderLine')
            ->will($this->returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        $this->assertInstanceOf(Accept::class, $scope->getAccept($request));
    }
}
