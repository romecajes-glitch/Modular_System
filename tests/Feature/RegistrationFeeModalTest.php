<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;

class RegistrationFeeModalTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $program;
    protected $enrollment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a program with registration fee
        $this->program = Program::factory()->create([
            'name' => 'Test Program',
            'registration_fee' => 500.00,
        ]);

        // Create a student user
        $this->student = User::factory()->create([
            'role' => 'student',
        ]);

        // Create enrollment
        $this->enrollment = Enrollment::factory()->create([
            'student_id' => $this->student->id,
            'program_id' => $this->program->id,
            'status' => Enrollment::STATUS_APPROVED,
            'registration_fee_paid' => false,
        ]);
    }

    /** @test */
    public function registration_fee_modal_appears_when_fee_unpaid_and_enrollment_approved()
    {
        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that the modal appears
        $response->assertStatus(200)
                ->assertSee('Registration Fee Required')
                ->assertSee('₱500.00')
                ->assertSee('Pay Registration Fee')
                ->assertSee('Contact Administrator');
    }

    /** @test */
    public function registration_fee_modal_does_not_appear_when_fee_paid()
    {
        // Update enrollment to mark registration fee as paid
        $this->enrollment->update(['registration_fee_paid' => true]);

        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that the modal does not appear
        $response->assertStatus(200)
                ->assertDontSee('Registration Fee Required');
    }

    /** @test */
    public function registration_fee_modal_does_not_appear_when_enrollment_pending()
    {
        // Update enrollment status to pending
        $this->enrollment->update(['status' => Enrollment::STATUS_PENDING]);

        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that the modal does not appear
        $response->assertStatus(200)
                ->assertDontSee('Registration Fee Required');
    }

    /** @test */
    public function registration_fee_modal_does_not_appear_when_enrollment_rejected()
    {
        // Update enrollment status to rejected
        $this->enrollment->update(['status' => Enrollment::STATUS_REJECTED]);

        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that the modal does not appear
        $response->assertStatus(200)
                ->assertDontSee('Registration Fee Required');
    }

    /** @test */
    public function registration_fee_modal_appears_on_attendance_page()
    {
        // Act as the student
        $this->actingAs($this->student);

        // Visit attendance page
        $response = $this->get(route('student.attendance'));

        // Assert that the modal appears
        $response->assertStatus(200)
                ->assertSee('Registration Fee Required')
                ->assertSee('₱500.00');
    }

    /** @test */
    public function registration_fee_modal_appears_on_payment_page()
    {
        // Act as the student
        $this->actingAs($this->student);

        // Visit payment page
        $response = $this->get(route('student.payment'));

        // Assert that the modal appears
        $response->assertStatus(200)
                ->assertSee('Registration Fee Required')
                ->assertSee('₱500.00');
    }

    /** @test */
    public function registration_fee_modal_appears_on_certificate_page()
    {
        // Act as the student
        $this->actingAs($this->student);

        // Visit certificate page
        $response = $this->get(route('student.certificate'));

        // Assert that the modal appears
        $response->assertStatus(200)
                ->assertSee('Registration Fee Required')
                ->assertSee('₱500.00');
    }

    /** @test */
    public function modal_shows_correct_registration_fee_amount()
    {
        // Update program registration fee
        $this->program->update(['registration_fee' => 750.00]);

        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that the correct amount is shown
        $response->assertStatus(200)
                ->assertSee('₱750.00');
    }

    /** @test */
    public function modal_does_not_appear_when_registration_fee_is_zero()
    {
        // Update program registration fee to zero
        $this->program->update(['registration_fee' => 0.00]);

        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that the modal does not appear
        $response->assertStatus(200)
                ->assertDontSee('Registration Fee Required');
    }

    /** @test */
    public function modal_contains_javascript_for_automatic_display()
    {
        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that JavaScript for modal display is present
        $response->assertStatus(200)
                ->assertSee('DOMContentLoaded')
                ->assertSee('registrationFeeModal')
                ->assertSee('modal.style.display = \'flex\'');
    }

    /** @test */
    public function modal_has_correct_css_classes_for_overlay()
    {
        // Act as the student
        $this->actingAs($this->student);

        // Visit dashboard
        $response = $this->get(route('student.dashboard'));

        // Assert that modal has correct overlay classes
        $response->assertStatus(200)
                ->assertSee('fixed inset-0 bg-black bg-opacity-50')
                ->assertSee('flex items-center justify-center z-50');
    }
}
