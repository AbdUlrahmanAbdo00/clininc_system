<?php 
use Illuminate\Support\Str;

$raw = env('FIREBASE_CREDENTIALS');

// إزالة العلامات الزائدة إذا موجودة
if (Str::startsWith($raw, '"') && Str::endsWith($raw, '"')) {
    $raw = substr($raw, 1, -1); // إزالة أول وآخر " من النص
    $raw = str_replace('\"', '"', $raw); // إعادة الاقتباسات الداخلية لوضعها الصحيح
    $raw = str_replace('\\n', "\n", $raw); // تحويل \n لنهاية سطر حقيقية
}

return [
'credentials' => json_decode($raw, true),
];