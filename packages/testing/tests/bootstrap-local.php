<?php declare(strict_types=1);

DG\BypassFinals::enable();
DG\BypassFinals::setWhitelist([
    '*/phpunit/src/Framework/MockObject/MockBuilder.php',
]);
