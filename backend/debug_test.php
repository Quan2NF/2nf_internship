<?php
// Quick test to verify validation
require 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\TestCase;

// This is just to check if unique validation works

// Create a user
$user = new User();
$user->email = 'test@example.com';
$user->employee_code = 'EMP-TEST';

// Now try to create another with the same email
$validator = \Illuminate\Support\Facades\Validator::make([
    'email' => 'test@example.com',
    'employee_code' => 'EMP-002'
], [
    'email' => ['required', 'email', 'unique:users,email'],
]);

echo "Validation passes: " . ($validator->passes() ? "YES" : "NO") . "\n";
echo "Errors: " . json_encode($validator->errors()->toArray()) . "\n";
