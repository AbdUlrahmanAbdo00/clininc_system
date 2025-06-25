<?php 
use Illuminate\Support\Str;

$raw = env('FIREBASE_CREDENTIALS');

if (Str::startsWith($raw, '"') && Str::endsWith($raw, '"')) {
    $raw = substr($raw, 1, -1);
    $raw = str_replace('\"', '"', $raw);
    $raw = str_replace('\\n', "\n", $raw); 
}

return [
'credentials' => json_decode($raw, true),
];