<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Enrollment;

class BatchNumberService
{
    /**
     * Calculate the batch number based on enrollment date
     * 
     * @param Carbon $enrollmentDate
     * @return int
     */
    public static function calculateBatchNumber(Carbon $enrollmentDate): int
    {
        // Get the first enrollment date to determine the starting point
        $firstEnrollment = Enrollment::orderBy('created_at', 'asc')->first();
        
        if (!$firstEnrollment) {
            return 1; // First enrollment ever
        }
        
        $firstEnrollmentDate = Carbon::parse($firstEnrollment->created_at);
        
        // Find the Saturday of the week for the first enrollment
        $firstSaturday = $firstEnrollmentDate->copy()->startOfWeek()->subDay(); // Saturday is start of week - 1 day
        
        // Calculate how many full weeks have passed since the first Saturday
        $weeksPassed = $enrollmentDate->copy()->startOfWeek()->subDay()->diffInWeeks($firstSaturday);
        
        // Batch number is weeks passed + 1 (since first batch is 1)
        return $weeksPassed + 1;
    }
    
    /**
     * Get the batch period for a given date
     * 
     * @param Carbon $date
     * @return array
     */
    public static function getBatchPeriod(Carbon $date): array
    {
        // Find the Saturday of the current week
        $saturday = $date->copy()->startOfWeek()->subDay();
        $friday = $saturday->copy()->addDays(6);
        
        return [
            'start' => $saturday,
            'end' => $friday
        ];
    }
    
    /**
     * Get the batch number for a given date
     * 
     * @param Carbon $date
     * @return int
     */
    public static function getBatchNumberForDate(Carbon $date): int
    {
        return self::calculateBatchNumber($date);
    }
}
