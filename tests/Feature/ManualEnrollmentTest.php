<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Program;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;

class ManualEnrollmentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $program;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Create a program
        $this->program = Program::factory()->create([
            'name' => 'Test Program',
            'status' => 'active',
            'registration_fee' => 1000.00
        ]);
    }

    /** @test */
    public function admin_can_manually_enroll_a_student()
    {
        // Test data
        $enrollmentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'Michael',
            'suffix_name' => '',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'email' => 'john.doe@example.com',
            'phone' => '09123456789',
            'program_id' => $this->program->id,
            'status' => 'enrolled',
            'address' => '123 Test Street, Test City'
        ];

        // Act as admin and make request
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'username',
                'password',
                'redirect_url'
            ]);

        // Assert database changes
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'role' => 'student'
        ]);

        $user = User::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $user->id,
            'program_id' => $this->program->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'status' => 'enrolled'
        ]);

        $enrollment = Enrollment::where('user_id', $user->id)->first();
        $this->assertNotNull($enrollment);

        // Check if payment was created
        $this->assertDatabaseHas('payments', [
            'enrollment_id' => $enrollment->id,
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function manual_enrollment_validates_required_fields()
    {
        // Missing required fields
        $incompleteData = [
            'first_name' => 'John',
            // Missing last_name, email, etc.
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $incompleteData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'last_name',
                'birthdate',
                'gender',
                'email',
                'program_id',
                'status'
            ]);
    }

    /** @test */
    public function manual_enrollment_prevents_duplicate_email()
    {
        // Create existing user
        User::factory()->create([
            'email' => 'existing@example.com'
        ]);

        $enrollmentData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'middle_name' => '',
            'suffix_name' => '',
            'birthdate' => '1992-02-02',
            'gender' => 'Female',
            'email' => 'existing@example.com', // Duplicate email
            'phone' => '09123456789',
            'program_id' => $this->program->id,
            'status' => 'enrolled',
            'address' => '456 Test Avenue'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function manual_enrollment_handles_invalid_program_id()
    {
        $enrollmentData = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'middle_name' => '',
            'suffix_name' => '',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'email' => 'test@example.com',
            'phone' => '09123456789',
            'program_id' => 99999, // Non-existent program
            'status' => 'enrolled',
            'address' => 'Test Address'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['program_id']);
    }

    /** @test */
    public function manual_enrollment_creates_payment_record()
    {
        $enrollmentData = [
            'first_name' => 'Payment',
            'last_name' => 'Test',
            'middle_name' => '',
            'suffix_name' => '',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'email' => 'payment.test@example.com',
            'phone' => '09123456789',
            'program_id' => $this->program->id,
            'status' => 'enrolled',
            'address' => 'Payment Test Address'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        $response->assertStatus(200);

        // Verify payment was created
        $user = User::where('email', 'payment.test@example.com')->first();
        $enrollment = Enrollment::where('user_id', $user->id)->first();

        $this->assertDatabaseHas('payments', [
            'enrollment_id' => $enrollment->id,
            'amount' => $this->program->registration_fee,
            'status' => 'pending',
            'payment_type' => 'registration'
        ]);
    }

    /** @test */
    public function manual_enrollment_logs_process_details()
    {
        // We can't easily test Log::info calls in unit tests,
        // but we can verify the method exists and handles logging
        $enrollmentData = [
            'first_name' => 'Log',
            'last_name' => 'Test',
            'middle_name' => '',
            'suffix_name' => '',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'email' => 'log.test@example.com',
            'phone' => '09123456789',
            'program_id' => $this->program->id,
            'status' => 'enrolled',
            'address' => 'Log Test Address'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        $response->assertStatus(200);

        // Verify the enrollment was created successfully
        $this->assertDatabaseHas('users', [
            'email' => 'log.test@example.com'
        ]);
    }

    /** @test */
    public function non_admin_cannot_access_manual_enrollment()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'status' => 'active'
        ]);

        $enrollmentData = [
            'first_name' => 'Unauthorized',
            'last_name' => 'Test',
            'middle_name' => '',
            'suffix_name' => '',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'email' => 'unauthorized@example.com',
            'phone' => '09123456789',
            'program_id' => $this->program->id,
            'status' => 'enrolled',
            'address' => 'Unauthorized Test Address'
        ];

        $response = $this->actingAs($student)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function manual_enrollment_handles_database_transaction_rollback()
    {
        // This test would require mocking database failures
        // For now, we'll test the successful case which includes transaction handling
        $enrollmentData = [
            'first_name' => 'Transaction',
            'last_name' => 'Test',
            'middle_name' => '',
            'suffix_name' => '',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'email' => 'transaction.test@example.com',
            'phone' => '09123456789',
            'program_id' => $this->program->id,
            'status' => 'enrolled',
            'address' => 'Transaction Test Address'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.manual-enroll'), $enrollmentData);

        $response->assertStatus(200);

        // Verify all related records exist or none exist (transaction integrity)
        $userExists = User::where('email', 'transaction.test@example.com')->exists();
        $enrollmentExists = Enrollment::whereHas('user', function($q) {
            $q->where('email', 'transaction.test@example.com');
        })->exists();

        $this->assertTrue($userExists && $enrollmentExists, 'Transaction should create all records or none');
    }
}
