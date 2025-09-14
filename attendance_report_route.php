Route::get('/attendance-records', [AdminController::class, 'attendanceRecords'])->name('admin.attendance.records');
    Route::get('/attendance-report', [AdminController::class, 'attendanceReport'])->name('admin.attendance.report');
