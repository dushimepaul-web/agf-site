<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$BASE = 'http://localhost/agf';
$EMAIL = 'admin@agf.bi';
$PASS = 'TestApi2026!';

class Jar {
    public $cookies = [];
    public function absorb($headers) {
        if (preg_match_all('/Set-Cookie:\s*([^=]+)=([^;\r\n]*)/i', $headers, $m, PREG_SET_ORDER)) {
            foreach ($m as $c) {
                $name = trim($c[1]); $val = trim($c[2]);
                if ($val === '' || $val === 'deleted') unset($this->cookies[$name]);
                else $this->cookies[$name] = $val;
            }
        }
    }
    public function header() {
        $p = [];
        foreach ($this->cookies as $k => $v) $p[] = "$k=$v";
        return $p ? 'Cookie: ' . implode('; ', $p) : null;
    }
}

function req($jar, $method, $url, $post = null, $asJson = true) {
    $ch = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => false,
    ];
    $h = [];
    $chdr = $jar->header();
    if ($chdr) $h[] = $chdr;
    if ($method === 'POST') {
        $opts[CURLOPT_POST] = true;
        if ($asJson) {
            $opts[CURLOPT_POSTFIELDS] = is_array($post) ? json_encode($post) : (string)$post;
            $h[] = 'Content-Type: application/json';
        } else {
            $opts[CURLOPT_POSTFIELDS] = is_array($post) ? http_build_query($post) : (string)$post;
            $h[] = 'Content-Type: application/x-www-form-urlencoded';
        }
        $h[] = 'X-Requested-With: XMLHttpRequest';
    }
    if ($method === 'GET') $h[] = 'X-Requested-With: XMLHttpRequest';
    if ($h) $opts[CURLOPT_HTTPHEADER] = $h;
    curl_setopt_array($ch, $opts);
    $resp = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    $headers = substr($resp, 0, $headerSize);
    $body = substr($resp, $headerSize);
    $jar->absorb($headers);
    return [$status, $body];
}

// --- Login ---
$jar = new Jar();
list($st, $body) = req($jar, 'GET', "$BASE/Admin");
preg_match('/name="csrf_token"\s+value="([^"]+)"/', $body, $m);
$csrf = $m[1] ?? '';
list($st, $body) = req($jar, 'POST', "$BASE/Admin/do_login", [
    'email' => $EMAIL, 'password' => $PASS, 'csrf_token' => $csrf,
], false);
$loc = '';
if (preg_match('/^Location:\s*(.+)$/mi', $body, $lm)) $loc = trim($lm[1]);
// body includes headers because req returns only body... fix: check status
if ($st >= 400 && $st !== 303) {
    // maybe 302/303 handled: curl FOLLOWLOCATION is false so 302 shows as status
}
echo "Login: HTTP $st\n";
if ($st !== 302 && $st !== 303 && $st !== 200) { echo "LOGIN FAILED\n"; exit(1); }
// refresh csrf
preg_match('/csrf_cookie=([^;]+)/', json_encode($jar->cookies), $cm);
if ($cm) $csrf = urldecode($cm[1]);

// verify session
list($st, $body) = req($jar, 'GET', "$BASE/Dashboard");
$authed = (stripos($body, 'Logout') !== false || stripos($body, 'logout') !== false);
echo "Session: " . ($authed ? 'OK' : 'FAILED') . "\n\n";
if (!$authed) exit(1);

// Helper: fetch first item id/uuid from api_list
function firstId($jar, $BASE, $listPath, $prefer = 'uuid') {
    list($st, $body) = req($jar, 'GET', "$BASE/$listPath");
    $j = json_decode($body, true);
    if (!is_array($j) || empty($j['data']) || !is_array($j['data'])) return null;
    $row = $j['data'][0];
    if ($prefer === 'uuid' && !empty($row['uuid'])) return $row['uuid'];
    foreach (['id','id_media','id_faq','id_partenaire','IdContact','id_menu'] as $k) {
        if (!empty($row[$k])) return $row[$k];
    }
    return $row[$prefer] ?? null;
}

$results = [];
$pass=0;$warn=0;$fail=0;

function test($method, $path, $data, $desc, $expectId = null) {
    global $jar, $BASE, $csrf, $pass, $warn, $fail;
    $post = $data;
    if ($post !== null) { $post['csrf_token'] = $csrf; }
    list($st, $body) = req($jar, $method, "$BASE/$path", $post);
    $json = json_decode($body, true);
    $ok=false; $note='';
    if ($st>=200 && $st<300) {
        if (is_array($json) && array_key_exists('success',$json)) {
            if ($json['success']===true) { $ok=true; $note='success=true'; }
            else $note='success=false: '.($json['message']??'?');
        } elseif (is_array($json)) { $ok=true; $note='JSON('.count($json).')'; }
        else $note='HTML 200';
    } elseif ($st===401) $note='401 non auth';
    elseif ($st===403) { $j=json_decode($body,true); $note='403: '.($j['message']??substr(strip_tags($body),0,60)); }
    elseif ($st===404) { $j=json_decode($body,true); $note='404: '.($j['message']??'introuvable'); }
    elseif ($st>=500) { $j=json_decode($body,true); $note='500: '.($j['message']??substr(strip_tags($body),0,150)); }
    else $note="HTTP $st";
    if ($ok) { $pass++; $tag='PASS'; }
    elseif ($st>=500) { $fail++; $tag='FAIL'; }
    elseif ($st===404) { $warn++; $tag='WARN'; }
    else { $warn++; $tag='WARN'; }
    printf("%-5s [%-4s] %-42s %s\n", $tag, $method, $path, $note);
}

// ===== TESTS =====
// Dashboard
test('GET', 'Dashboard/api_data?days=30', null, 'Dashboard api_data');
test('GET', 'Dashboard/api_filters', null, 'Dashboard api_filters');

// Users (uuid)
$uid = firstId($jar, $BASE, 'Users/api_list', 'uuid');
echo "-- Users uuid: $uid --\n";
test('GET', 'Users/api_list', null, 'Users list');
test('GET', "Users/api_get/$uid", null, 'Users get(uuid)');
test('POST', 'Users/api_create', ['username'=>'test_api','email'=>'testapi@agf.cd','nom'=>'Test','prenom'=>'API','password'=>'Test1234!','role_id'=>3], 'Users create');
$newU = firstId($jar, $BASE, 'Users/api_list', 'uuid'); // may be test_api
test('POST', "Users/api_update/$newU", ['prenom'=>'API2'], 'Users update(uuid)');
test('POST', "Users/api_delete/$newU", [], 'Users delete(uuid)');

// Roles (uuid) — api_create attend `nom` (pas libelle)
$r1 = firstId($jar, $BASE, 'Roles/api_list', 'uuid');
echo "-- Roles uuid: $r1 --\n";
test('GET', 'Roles/api_list', null, 'Roles list');
test('GET', "Roles/api_get/$r1", null, 'Roles get(uuid)');
test('POST', 'Roles/api_create', ['code'=>'TEST_ROLE','nom'=>'Role Test'], 'Roles create');
$r2 = firstId($jar, $BASE, 'Roles/api_list', 'uuid'); // may be TEST_ROLE
test('POST', "Roles/api_update/$r2", ['nom'=>'Role Test Upd'], 'Roles update(uuid)');
test('POST', "Roles/api_delete/$r2", [], 'Roles delete(uuid)');

// Menus (uuid)
$m1 = firstId($jar, $BASE, 'Menus/api_list', 'uuid');
echo "-- Menus uuid: $m1 --\n";
test('GET', 'Menus/api_list', null, 'Menus list');
test('GET', "Menus/api_get/$m1", null, 'Menus get(uuid)');
test('POST', 'Menus/api_create', ['libelle'=>'Menu Test','code'=>'menu_test','lien'=>'#','position'=>99,'est_actif'=>1], 'Menus create');

// Logs (id) — table vide sauf audit récent : on prend le dernier id via api_list
list($st, $body) = req($jar, 'GET', "$BASE/Logs/api_list?limit=1");
$lj = json_decode($body, true);
$logId = $lj['data'][0]['id'] ?? null;
echo "-- Logs last id: $logId --\n";
test('GET', 'Logs/api_list?limit=5', null, 'Logs list');
if ($logId) test('GET', "Logs/api_get/$logId", null, 'Logs get(id)');

// Sessions / Visitors
test('GET', 'Sessions/api_list', null, 'Sessions list');
test('GET', 'Visitors/api_list', null, 'Visitors list');
test('GET', 'Visitors/api_stats', null, 'Visitors stats');

// Produits (id) — ids réels commencent à 23 ; create attend categorie_id valide
test('GET', 'Produits/api_list', null, 'Produits list');
test('GET', 'Produits/api_get/23', null, 'Produits get(id=23)');
test('POST', 'Produits/api_create', ['nom'=>'Test API prod','categorie_id'=>4,'description'=>'test'], 'Produits create');

// Categories (id) — ids commencent à 4 ; create attend `nom`
test('GET', 'Categories/api_list', null, 'Categories list');
test('GET', 'Categories/api_get/4', null, 'Categories get(id=4)');
test('POST', 'Categories/api_create', ['nom'=>'Cat Test API'], 'Categories create');

// Unites (id) — create attend `code` + `nom`
test('GET', 'Unites/api_list', null, 'Unites list');
test('GET', 'Unites/api_get/1', null, 'Unites get(id)');
test('POST', 'Unites/api_create', ['code'=>'UTX','nom'=>'Unite Test API'], 'Unites create');

// ContactUs (id) — ids commencent à 13
test('GET', 'ContactUs/api_list', null, 'ContactUs list');
test('GET', 'ContactUs/api_get/13', null, 'ContactUs get(id=13)');
test('GET', 'ContactUs/api_read/13', null, 'ContactUs read(id=13)');

// Faq (id) — ids commencent à 8
test('GET', 'Faq/api_list', null, 'Faq list');
test('GET', 'Faq/api_get/8', null, 'Faq get(id=8)');
test('POST', 'Faq/api_create', ['question'=>'Q test?','reponse'=>'R test','est_publiee'=>1], 'Faq create');

// Partenaires (id) — create attend `nom` + `type_partenaire`
test('GET', 'Partenaires/api_list', null, 'Partenaires list');
test('GET', 'Partenaires/api_get/1', null, 'Partenaires get(id)');
test('POST', 'Partenaires/api_create', ['nom'=>'Part Test','type_partenaire'=>'commercial'], 'Partenaires create');

// SocialLinks (id) — create attend `platform` + `url`
test('GET', 'SocialLinks/api_list', null, 'SocialLinks list');
test('GET', 'SocialLinks/api_get/1', null, 'SocialLinks get(id)');
test('POST', 'SocialLinks/api_create', ['platform'=>'facebook','url'=>'https://facebook.com/test'], 'SocialLinks create');

// Investors (id) — ids commencent à 6 ; create attend `full_name` + `email`
test('GET', 'Investors/api_list', null, 'Investors list');
test('GET', 'Investors/api_get/6', null, 'Investors get(id=6)');
test('POST', 'Investors/api_create', ['full_name'=>'Inv Test','email'=>'inv@test.cd'], 'Investors create');

// Brokers (id) — ids commencent à 26 ; create attend `full_name` + `firm_name` + `email`
test('GET', 'Brokers/api_list', null, 'Brokers list');
test('GET', 'Brokers/api_get/26', null, 'Brokers get(id=26)');
test('POST', 'Brokers/api_create', ['full_name'=>'Brk Test','firm_name'=>'Firm Test','email'=>'brk@test.cd'], 'Brokers create');

// Temoignages (id)
test('GET', 'Temoignages/api_list', null, 'Temoignages list');

// Consultations (uuid)
$c1 = firstId($jar, $BASE, 'Consultations/api_list', 'uuid');
echo "-- Consultations uuid: $c1 --\n";
test('GET', 'Consultations/api_list', null, 'Consultations list');
if ($c1) test('GET', "Consultations/api_get/$c1", null, 'Consultations get(uuid)');
test('GET', 'Consultations/api_medecins_list', null, 'Medecins list');
// medecins api_get attend un uuid
$medList = json_decode(req($jar, 'GET', "$BASE/Consultations/api_medecins_list")[1], true);
$medUuid = $medList['data'][0]['uuid'] ?? null;
if ($medUuid) test('GET', "Consultations/api_medecins_get/$medUuid", null, 'Medecins get(uuid)');

// Patients
test('GET', 'Patients/api_list', null, 'Patients list');

// Parametres
test('GET', 'Parametres/api_list', null, 'Parametres list');

// Profile
test('POST', 'Profile/api_update', ['nom'=>'Admin','prenom'=>'Test','telephone'=>'+243800000000'], 'Profile update');

echo "\n=== RESUME: $pass PASS / $warn WARN / $fail FAIL ===\n";
