--TEST--
secp256k1_schnorrsig_sign works a user provided nonce function, with no additional data
--SKIPIF--
<?php
if (!extension_loaded("secp256k1")) print "skip extension not loaded";
if (!function_exists("secp256k1_schnorrsig_verify")) print "skip no schnorrsig support";
?>
--FILE--
<?php

$ctx = secp256k1_context_create(SECP256K1_CONTEXT_SIGN);

$hashFxn = function (&$nonce, string $msg,
    string $key32, string $xonlyPk32, string $algo16, $data) {
    echo "triggered callback\n";
    var_dump($data);
    $nonce = hex2bin("1d2dc1652fee3ad08434469f9ad30536a5787feccfa308e8fb396c8030dd1c69");
    return 1;
};

$sig = null;
$output = '';
$privKey = hex2bin("0000000000000000000000000000000000000000000000000000000000000003");
$msg32 = hex2bin("0000000000000000000000000000000000000000000000000000000000000000");
$auxRand = hex2bin("0000000000000000000000000000000000000000000000000000000000000000");

$keypair = null;
$result = secp256k1_keypair_create($ctx, $keypair, $privKey);
echo $result.PHP_EOL;

$result = secp256k1_schnorrsig_sign($ctx, $sig, $msg32, $keypair, $hashFxn, NULL);
echo $result.PHP_EOL;

echo strtoupper(unpack("H*", $sig)[1]).PHP_EOL;

?>
--EXPECT--
1
triggered callback
NULL
1
E907831F80848D1069A5371B402410364BDF1C5F8307B0084C55F1CE2DCA821525F66A4A85EA8B71E482A74F382D2CE5EBEEE8FDB2172F477DF4900D310536C0