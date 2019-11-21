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
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Language scope tests.
 */
class LanguageTest extends TestCase
{
    public function testDefaultAccept(): void
    {
        $scope = new Language(['es'], true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->will(static::returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        $accept = $scope->getAccept($request);

        static::assertInstanceOf(AcceptLanguage::class, $accept);
        static::assertEquals('es', $accept->getValue());
    }
}
