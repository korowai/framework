<?php
/* [code] */
/* [use] */
use Korowai\Lib\Rfc\Rfc2253;
/* [/use] */

/* [dnRule] */
$dnRule = Rfc2253::regexp('DISTINGUISHED_NAME');
/* [/dnRule] */

/* [preg_match] */
$result = preg_match('/^'.$dnRule.'$/', 'cn=admin,dc=example,dc=org', $matches, PREG_UNMATCHED_AS_NULL);
print("result: $result\n");
/* [/preg_match] */

/* [captures] */
print("captures: ".json_encode(Rfc2253::captures('DISTINGUISHED_NAME'))."\n");
print("valueCaptures: ".json_encode(Rfc2253::valueCaptures('DISTINGUISHED_NAME'))."\n");
print("errorCaptures: ".json_encode(Rfc2253::errorCaptures('DISTINGUISHED_NAME'))."\n");
/* [/captures] */

/* [captured] */
print("findCapturedValues: ".json_encode(Rfc2253::findCapturedValues('DISTINGUISHED_NAME', $matches))."\n");
print("findCapturedErrors: ".json_encode(Rfc2253::findCapturedErrors('DISTINGUISHED_NAME', $matches))."\n");
/* [/captured] */

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
