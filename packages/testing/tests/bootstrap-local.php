<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

DG\BypassFinals::enable();
DG\BypassFinals::setWhitelist([
    '*/phpunit/src/Framework/MockObject/MockBuilder.php',
]);
