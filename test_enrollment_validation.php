<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

// Simulate the enrollment data from your console log
$testData = [
    '_token' => '8vB6aRRLZR9BfwwZE9VsAG6FHKTok6eUlOVuBUhT',
    'first_name' => 'Danilo',
    'last_name' => 'Gucor',
    'middle_name' => 'Aton',
    'suffix_name' => '',
    'birthdate' => '2002-03-20',
    'age' => '23',
    'gender' => 'Male',
    'email' => 'geromecajes123@gmail.com',
    'phone' => '09308365866',
    'address' => 'Soom, Trinidad, Bohol',
    'citizenship' => 'Filipino',
    'religion' => 'Roman Catholic',
    'place_of_birth' => 'La union, Trinidad, Bohol',
    'civil_status' => 'Single',
    'spouse_name' => '',
    'father_name' => 'Generoso Cajes',
    'mother_name' => 'Luzviminda Cajes',
    'guardian' => 'Luzviminda Cajes',
    'guardian_contact' => '09308365866',
    'program_id' => '1',
    'qr_pin' => 'fbNl7eLt',
    'certify_true' => 'on',
    // Note: photo and parent_consent are files, so we can't simulate them easily
];

// Test the validation rules
$validator = Validator::make(
    $testData,
    [
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'suffix_name' => 'nullable|string|max:10',
        'birthdate' => 'required|date',
        'age' => 'required|integer|min:0',
        'gender' => 'required|string|max:10',
        'email' => 'required|email|unique:users,email',
        'phone' => ['nullable', 'regex:/^09[0-9]{9}$/'],
        'address' => 'nullable|string|max:255',
        'citizenship' => 'nullable|string|max:100',
        'religion' => 'nullable|string|max:100',
        'place_of_birth' => 'nullable|string|max:255',
        'civil_status' => 'nullable|string|max:20',
        'spouse_name' => 'nullable|string|max:255',
        'father_name' => 'nullable|string|max:255',
        'mother_name' => 'nullable|string|max:255',
        'guardian' => 'nullable|string|max:255',
        'guardian_contact' => ['nullable', 'regex:/^09[0-9]{9}$/'],
        'program_id' => 'required|string',
        'certify_true' => 'accepted',
        'qr_pin' => [
            'required',
            'string',
            'size:8',
            function ($attribute, $value, $fail) {
                $exists = DB::table('qr_codes')
                    ->where('unique_pin', $value)
                    ->where('is_used', false)
                    ->exists();
                if (!$exists) {
                    $fail('QR Code PIN is incorrect or already used.');
                }
            }
        ],
    ]
);

// Custom validation for father, mother, and guardian fields
$validator->after(function ($validator) use ($testData) {
    $fatherName = $testData['father_name'];
    $motherName = $testData['mother_name'];
    $guardian = $testData['guardian'];
    
    // Check if all three fields are empty
    if (empty($fatherName) && empty($motherName) && empty($guardian)) {
        $validator->errors()->add(
            'father_name', 
            'At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'
        );
        $validator->errors()->add(
            'mother_name', 
            'At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'
        );
        $validator->errors()->add(
            'guardian', 
            'At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'
        );
    }
});

if ($validator->fails()) {
    echo "Validation failed with errors:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "- " . $error . "\n";
    }
} else {
    echo "Validation passed! The data should be valid.\n";
    
    // Check if email already exists in users table
    $emailExists = DB::table('users')->where('email', $testData['email'])->exists();
    if ($emailExists) {
        echo "WARNING: Email '{$testData['email']}' already exists in users table!\n";
    } else {
        echo "Email '{$testData['email']}' is available.\n";
    }
}
?>
