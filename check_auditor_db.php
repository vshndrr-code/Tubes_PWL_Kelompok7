<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('email', 'auditor@moma.com')->first();
if ($user) {
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "IsAuditor(): " . ($user->isAuditor() ? 'true' : 'false') . "\n";
} else {
    echo "User not found!\n";
}
