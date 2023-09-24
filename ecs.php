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

use Jgut\ECS\Config\ConfigSet81;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$header = <<<'HEADER'
negotiate (https://github.com/juliangut/negotiate).
Negotiation middleware.

@license BSD-3-Clause
@link https://github.com/juliangut/negotiate
@author Julián Gutiérrez <juliangut@gmail.com>
HEADER;

return static function (ECSConfig $ecsConfig) use ($header): void {
    $ecsConfig->paths([
        __FILE__,
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $configSet = new ConfigSet81();

    $configSet
        ->setHeader($header)
        ->enablePhpUnitRules()
        ->configure($ecsConfig);
};
