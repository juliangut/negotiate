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
 * @internal
 */
class ProviderTest extends TestCase
{
    public function testAccess(): void
    {
        $mediaType = new AcceptStub('application/json');

        $provider = new Provider(['mediaType' => $mediaType]);

        static::assertSame($mediaType, $provider->get('mediaType'));
        static::assertSame($mediaType, $provider->getMediaType());
        static::assertSame('application/json', $provider->getMediaTypeLine());
        static::assertNull($provider->getUnknown());
        static::assertNull($provider->unknown());
    }
}
