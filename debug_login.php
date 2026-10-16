<?php
error_reporting(E_ALL);
$ck = tempnam(sys_get_temp_dir(), 'ck');
$ch = curl_init('http://localhost/agf/Admin');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_COOKIEJAR => $ck,
    CURLOPT_COOKIEFILE => $ck,
]);
$r = curl_exec($ch);
curl_close($ch);
echo "=== SET-COOKIE ===\n";
preg_match_all('/Set-Cookie: ([^\r\n]+)/i', $r, $m);
print_r($m[1]);
echo "=== COOKIE FILE ===\n";
echo file_get_contents($ck);
echo "\n=== CSRF HIDDEN INPUT ===\n";
if (preg_match('/<input[^>]*csrf[^>]*>/i', $r, $mm)) echo $mm[0] . "\n";
else echo "NO CSRF INPUT FOUND\n";
echo "=== FORM ACTION ===\n";
if (preg_match_all('/<form[^>]*>/i', $r, $ff)) print_r($ff[0]);
echo "=== EMAIL/USER FIELDS ===\n";
preg_match_all('/<input[^>]*(?:email|username|password)[^>]*>/i', $r, $ii);
print_r($ii[0]);

// Now login
$csrf = '';
if (preg_match('/csrf_cookie=([^;]+)/', file_get_contents($ck), $cm)) $csrf = urldecode($cm[1]);
if ($csrf === '' && preg_match('/name="csrf_token"\s+value="([^"]+)"/', $r, $hm)) $csrf = $hm[1];
echo "\n=== CSRF TOKEN: '$csrf' ===\n";

$ch2 = curl_init('http://localhost/agf/Admin/do_login');
curl_setopt_array($ch2, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'email' => 'admin@agf.bi',
        'password' => 'TestApi2026!',
        'csrf_token' => $csrf,
    ]),
    CURLOPT_COOKIEJAR => $ck,
    CURLOPT_COOKIEFILE => $ck,
]);
$r2 = curl_exec($ch2);
$code = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);
echo "\n=== LOGIN HTTP $code ===\n";
// Show headers + body start
$parts = explode("\r\n\r\n", $r2, 2);
echo substr($parts[0], 0, 800) . "\n---BODY---\n";
echo substr($parts[1] ?? '', 0, 500) . "\n";
echo "\n=== COOKIE AFTER LOGIN ===\n";
echo file_get_contents($ck) . "\n";
