<?php
/* [use] */
use function Korowai\Lib\Context\with;

/* [withFopenDoFread] */
with(fopen(__DIR__.'/hello.txt', 'r'))->do(function($fd) {
  echo fread($fd, 20)."\n";
});
