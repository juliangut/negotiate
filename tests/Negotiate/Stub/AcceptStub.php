<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Tests\Stub;

use Negotiation\AcceptHeader;
use Negotiation\BaseAccept;

/**
 * @internal
 */
final class AcceptStub extends BaseAccept implements AcceptHeader {}
