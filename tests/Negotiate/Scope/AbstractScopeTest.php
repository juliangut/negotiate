<?php

/*
 * (c) 2017-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Tests\Scope;

use Jgut\Negotiate\Tests\Stub\ScopeStub;
use Negotiation\Negotiator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AbstractScopeTest extends TestCase
{
    public function testPriorityList(): void
    {
        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $scope = new ScopeStub('X-Custom', ['one', 'two'], $negotiator);

        static::assertSame(['one', 'two'], $scope->getPriorityList());

        $scope->prependPriority('zero');
        $scope->appendPriority('last');

        static::assertSame(['zero', 'one', 'two', 'last'], $scope->getPriorityList());
    }
}
