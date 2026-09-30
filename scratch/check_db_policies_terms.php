<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Policy;

$policies = Policy::all();
foreach ($policies as $p) {
    if (str_contains($p->content_bm ?? '', 'Perolehan Tersuai') || str_contains($p->content_bm ?? '', 'perolehan tersuai')) {
        echo "Policy {$p->slug} content_bm contains Perolehan Tersuai\n";
    }
    if (str_contains($p->content_bm ?? '', 'Akaun Perdagangan') || str_contains($p->content_bm ?? '', 'Harga Perdagangan')) {
        echo "Policy {$p->slug} content_bm contains Akaun/Harga Perdagangan\n";
    }
}
