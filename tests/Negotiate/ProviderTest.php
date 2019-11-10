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

namespace Jgut\Negotiate\Tests;

use Jgut\Negotiate\Provider;
use Jgut\Negotiate\Tests\Stub\AcceptStub;
use PHPUnit\Framework\TestCase;

/**
 * Negotiation provider tests.
 */
class ProviderTest extends TestCase
{
    public function testAccess(): void
    {
        $provider = new Provider();

        $mediaType = new AcceptStub('application/json');

        $provider->addAccept('mediaType', $mediaType);

        static::assertEquals($mediaType, $provider->get('mediaType'));
        static::assertEquals($mediaType, $provider->getMediaType());
        static::assertEquals('application/json', $provider->getMediaTypeLine());
        static::assertNull($provider->getUnknown());
        static::assertNull($provider->unknown());
    }
}
