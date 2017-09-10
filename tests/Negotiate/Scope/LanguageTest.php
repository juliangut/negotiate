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

use Jgut\Negotiate\Scope\Language;
use Negotiation\AcceptLanguage;
use Negotiation\LanguageNegotiator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Language scope tests.
 */
class LanguageTest extends TestCase
{
    public function testDefaultAccept()
    {
        $negotiator = $this->getMockBuilder(LanguageNegotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects($this->once())
            ->method('getBest')
            ->will($this->returnValue(null));
        /* @var LanguageNegotiator $negotiator */

        $scope = new Language(['es'], $negotiator, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getHeaderLine')
            ->will($this->returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        $this->assertInstanceOf(AcceptLanguage::class, $scope->getAccept($request));
    }
}
