<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing database connection and program data...\n";

try {
    // Test database connection
    $pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Database connection: OK\n";
    
    // Test program data
    $programCount = App\Models\Program::count();
    echo "Total programs: " . $programCount . "\n";
    
    if ($programCount > 0) {
        $program = App\Models\Program::first();
        echo "First program: " . $program->id . " - " . $program->name . "\n";
        echo "Price per session: ₱" . $program->price_per_session . "\n";
        
        // Update program with price if needed
        if (!$program->price_per_session) {
            $program->price_per_session = 500.00;
            $program->save();
            echo "Updated program price to ₱500.00\n";
        }
    } else {
        // Create a sample program
        $program = App\Models\Program::create([
            'name' => 'Web Development',
            'duration' => '3 months',
            'description' => 'Learn web development fundamentals',
            'price_per_session' => 500.00
        ]);
        echo "Created sample program: " . $program->name . "\n";
    }
    
    // Test user with enrollment
    $user = App\Models\User::first();
    if ($user) {
        echo "First user: " . $user->id . " - " . $user->name . "\n";
        
        // Check if user has enrollment
        $enrollment = App\Models\Enrollment::where('email', $user->email)->first();
        if ($enrollment) {
            echo "Enrollment found: " . $enrollment->program . "\n";
            
            // Test program relationship
            $program = $enrollment->program()->first();
            if ($program) {
                echo "Program from enrollment: " . $program->name . "\n";
                echo "Program price: ₱" . $program->price_per_session . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
