@extends('layouts.admin')

@section('title', 'Onsite Payment Verification')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Onsite Payment Verification</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="onsitePaymentsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Program</th>
                                    <th>OR Number</th>
                                    <th>Submitted Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Onsite Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to confirm this onsite payment?</p>
                <div id="studentDetails"></div>
                <div class="form-group">
                    <label for="confirmationNotes">Notes (Optional)</label>
                    <textarea class="form-control" id="confirmationNotes" rows="3" placeholder="Add any notes about this payment confirmation..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmPaymentBtn">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Onsite Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this onsite payment?</p>
                <div id="rejectStudentDetails"></div>
                <div class="form-group">
                    <label for="rejectionReason">Rejection Reason <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="rejectionReason" rows="3" placeholder="Please provide a reason for rejecting this payment..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="rejectPaymentBtn">Reject Payment</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentEnrollmentId = null;

    // Load pending payments
    loadPendingPayments();

    function loadPendingPayments() {
        $.ajax({
            url: '{{ route("admin.onsite-payments.pending") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    displayPayments(response.pending_payments.data);
                } else {
                    toastr.error('Failed to load pending payments');
                }
            },
            error: function() {
                toastr.error('Error loading pending payments');
            }
        });
    }

    function displayPayments(payments) {
        const tbody = $('#paymentsTableBody');
        tbody.empty();

        if (payments.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No pending onsite payments to verify.
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        payments.forEach(function(payment) {
            const row = `
                <tr>
                    <td>${payment.user ? payment.user.name : 'N/A'}</td>
                    <td>${payment.email || 'N/A'}</td>
                    <td>${payment.program ? payment.program.name : 'N/A'}</td>
                    <td><code>${payment.or_number}</code></td>
                    <td>${new Date(payment.updated_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-sm btn-success confirm-btn" data-id="${payment.id}" data-student="${payment.user ? payment.user.name : 'N/A'}" data-program="${payment.program ? payment.program.name : 'N/A'}" data-or="${payment.or_number}">
                            <i class="fas fa-check"></i> Confirm
                        </button>
                        <button class="btn btn-sm btn-danger reject-btn" data-id="${payment.id}" data-student="${payment.user ? payment.user.name : 'N/A'}" data-program="${payment.program ? payment.program.name : 'N/A'}">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Confirm payment button click
    $(document).on('click', '.confirm-btn', function() {
        currentEnrollmentId = $(this).data('id');
        const studentName = $(this).data('student');
        const programName = $(this).data('program');
        const orNumber = $(this).data('or');

        $('#studentDetails').html(`
            <div class="alert alert-info">
                <strong>Student:</strong> ${studentName}<br>
                <strong>Program:</strong> ${programName}<br>
                <strong>OR Number:</strong> <code>${orNumber}</code>
            </div>
        `);

        $('#confirmModal').modal('show');
    });

    // Reject payment button click
    $(document).on('click', '.reject-btn', function() {
        currentEnrollmentId = $(this).data('id');
        const studentName = $(this).data('student');
        const programName = $(this).data('program');

        $('#rejectStudentDetails').html(`
            <div class="alert alert-warning">
                <strong>Student:</strong> ${studentName}<br>
                <strong>Program:</strong> ${programName}
            </div>
        `);

        $('#rejectModal').modal('show');
    });

    // Confirm payment
    $('#confirmPaymentBtn').click(function() {
        if (!currentEnrollmentId) return;

        const notes = $('#confirmationNotes').val();

        $.ajax({
            url: `{{ url('/admin/onsite-payments') }}/${currentEnrollmentId}/confirm`,
            method: 'POST',
            data: {
                notes: notes,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#confirmModal').modal('hide');
                    loadPendingPayments(); // Reload the table
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'Failed to confirm payment');
            }
        });
    });

    // Reject payment
    $('#rejectPaymentBtn').click(function() {
        if (!currentEnrollmentId) return;

        const reason = $('#rejectionReason').val().trim();

        if (!reason) {
            toastr.error('Please provide a rejection reason');
            return;
        }

        $.ajax({
            url: `{{ url('/admin/onsite-payments') }}/${currentEnrollmentId}/reject`,
            method: 'POST',
            data: {
                reason: reason,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#rejectModal').modal('hide');
                    $('#rejectionReason').val('');
                    loadPendingPayments(); // Reload the table
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'Failed to reject payment');
            }
        });
    });

    // Initialize DataTable
    $('#onsitePaymentsTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#onsitePaymentsTable_wrapper .col-md-6:eq(0)');
});
</script>
@endsection
