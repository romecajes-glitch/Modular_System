# Payment and Attendance System Fixes

## Issues to Fix:
1. Payment Session Count Not Displayed in Attendance Table
2. Session Dropdown in Payment Modal Not Matching Program Sessions
3. Data Consistency Between Payment and Attendance

## Implementation Plan:

### Step 1: Fix AdminController@attendance method
- [x] Change logic to sum all completed payments' session_count for each student
- [x] Update paidSessions calculation to be cumulative across all payments

### Step 2: Update attendance.blade.php
- [x] Ensure attendance table uses correct paidSessions value
- [x] Fix payment modal dropdown to use program's total sessions minus paidSessions

### Step 3: Update payment.blade.php
- [x] Fix session count dropdown to use program's total sessions minus paidSessions

### Step 4: Add helper methods (if needed)
- [x] Create method to get total paid sessions for a student
- [x] Create method to get program's total session count

### Step 5: Testing
- [ ] Test attendance table display
- [ ] Test payment modal dropdown options
- [ ] Test payment processing and attendance eligibility

## Current Status: Step 5 - Testing
