<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\QrCode;

class EnrollmentValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_that_at_least_one_parent_or_guardian_field_is_provided()
    {
        // Create a valid QR code first
        $qrCode = QrCode::create([
            'qr_id' => 'TEST123',
            'type' => 'enrollment',
            'data' => 'test data',
            'unique_pin' => '12345678',
            'is_used' => false,
        ]);

        $response = $this->postJson('/enroll', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birthdate' => '2000-01-01',
            'age' => 24,
            'gender' => 'Male',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'program_id' => '1',
            'recruiter' => 'Test Recruiter',
            'qr_pin' => '12345678',
            'certify_true' => '1',
            // Intentionally leaving father_name, mother_name, and guardian empty
            'father_name' => '',
            'mother_name' => '',
            'guardian' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['father_name', 'mother_name', 'guardian']);
        $response->assertJsonFragment([
            'father_name' => ['At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'],
            'mother_name' => ['At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'],
            'guardian' => ['At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'],
        ]);
    }

    /** @test */
    public function it_accepts_when_father_name_is_provided()
    {
        $qrCode = QrCode::create([
            'qr_id' => 'TEST123',
            'type' => 'enrollment',
            'data' => 'test data',
            'unique_pin' => '12345678',
            'is_used' => false,
        ]);

        $response = $this->postJson('/enroll', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birthdate' => '2000-01-01',
            'age' => 24,
            'gender' => 'Male',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'program_id' => '1',
            'recruiter' => 'Test Recruiter',
            'qr_pin' => '12345678',
            'certify_true' => '1',
            'father_name' => 'John Doe Sr.',
            'mother_name' => '',
            'guardian' => '',
        ]);

        $response->assertStatus(200); // Should succeed
    }

    /** @test */
    public function it_accepts_when_mother_name_is_provided()
    {
        $qrCode = QrCode::create([
            'qr_id' => 'TEST123',
            'type' => 'enrollment',
            'data' => 'test data',
            'unique_pin' => '12345678',
            'is_used' => false,
        ]);

        $response = $this->postJson('/enroll', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birthdate' => '2000-01-01',
            'age' => 24,
            'gender' => 'Male',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'program_id' => '1',
            'recruiter' => 'Test Recruiter',
            'qr_pin' => '12345678',
            'certify_true' => '1',
            'father_name' => '',
            'mother_name' => 'Jane Doe',
            'guardian' => '',
        ]);

        $response->assertStatus(200); // Should succeed
    }

    /** @test */
    public function it_accepts_when_guardian_is_provided()
    {
        $qrCode = QrCode::create([
            'qr_id' => 'TEST123',
            'type' => 'enrollment',
            'data' => 'test data',
            'unique_pin' => '12345678',
            'is_used' => false,
        ]);

        $response = $this->postJson('/enroll', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birthdate' => '2000-01-01',
            'age' => 24,
            'gender' => 'Male',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'program_id' => '1',
            'recruiter' => 'Test Recruiter',
            'qr_pin' => '12345678',
            'certify_true' => '1',
            'father_name' => '',
            'mother_name' => '',
            'guardian' => 'Grandparent Smith',
        ]);

        $response->assertStatus(200); // Should succeed
    }
}
