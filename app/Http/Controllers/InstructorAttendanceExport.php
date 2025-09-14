<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Collection;

class InstructorAttendanceExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithEvents, WithValidation
{
    protected $students;
    protected $sessionNumber;
    protected $sessionDate;

    public function __construct($students, $sessionNumber, $sessionDate)
    {
        $this->students = $students;
        $this->sessionNumber = $sessionNumber;
        $this->sessionDate = $sessionDate;
    }

    /**
     * Return collection of rows for export.
     */
    public function collection()
    {
        $rows = collect();

        foreach ($this->students as $student) {
            $enrollment = $student->enrollments->first();
            $paymentStatus = 'Pending Onsite';
            $orNumber = '';
            $status = '';

            // Determine payment status based on completed payments with transaction_id
            if ($student->payments->where('status', 'completed')->whereNotNull('transaction_id')->isNotEmpty()) {
                $paymentStatus = 'Paid Online';
            }

            $rows->push([
                'student_id' => $student->id,
                'student_name' => $student->name,
                'session_number' => $this->sessionNumber,
                'session_date' => $this->sessionDate,
                'payment_status' => $paymentStatus,
                'or_number' => $orNumber,
                'status' => $status,
            ]);
        }

        return $rows;
    }

    /**
     * Return headings for the Excel sheet.
     */
    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Session Number',
            'Session Date',
            'Payment Status',
            'OR Number',
            'Status',
        ];
    }

    /**
     * Define column widths for better readability.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12, // Student ID
            'B' => 25, // Student Name
            'C' => 15, // Session Number
            'D' => 15, // Session Date
            'E' => 18, // Payment Status
            'F' => 20, // OR Number
            'G' => 12, // Status
        ];
    }

    /**
     * Apply styles to the worksheet.
     */
    public function styles($sheet)
    {
        $lastRow = $this->students->count() + 1; // +1 for header row

        // Style for header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Apply header style
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Style for data rows
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        // Apply data style to all data rows
        if ($lastRow > 1) {
            $sheet->getStyle('A2:G' . $lastRow)->applyFromArray($dataStyle);
        }

        // Center align specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style for protected columns (light gray background)
        $protectedStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2'],
            ],
        ];

        // Apply protected style to columns A, B, C, D, E (Student ID, Name, Session Number, Date, Payment Status)
        $sheet->getStyle('A2:E' . $lastRow)->applyFromArray($protectedStyle);

        // Style for editable columns (light yellow background)
        $editableStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFE0'],
            ],
        ];

        // Apply editable style to columns F, G (OR Number, Status)
        $sheet->getStyle('F2:G' . $lastRow)->applyFromArray($editableStyle);

        return [
            // You can return specific styles here if needed
            1 => ['font' => ['bold' => true]], // Header row
        ];
    }

    /**
     * Register events for worksheet protection and data validation.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $this->students->count() + 1;

                // Protect the worksheet
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('attendance_template');

                // Unlock editable columns (F and G - OR Number and Status)
                $sheet->getStyle('F2:G' . $lastRow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

                // Lock all other columns
                $sheet->getStyle('A1:E' . $lastRow)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

                // Add data validation for Status column (dropdown with Present/Absent)
                for ($row = 2; $row <= $lastRow; $row++) {
                    $validation = $sheet->getCell('G' . $row)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid Status');
                    $validation->setError('Please select Present or Absent from the dropdown.');
                    $validation->setPromptTitle('Select Status');
                    $validation->setPrompt('Choose Present or Absent');
                    $validation->setFormula1('"Present,Absent"');
                }

                // Add conditional formatting for Payment Status
                $conditionalStyles = $sheet->getConditionalStyles('E2:E' . $lastRow);
                $conditionalStyles[] = new \PhpOffice\PhpSpreadsheet\Style\Conditional([
                    'condition' => 'E2="Paid Online"',
                    'style' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'D4EDDA'],
                        ],
                        'font' => [
                            'color' => ['rgb' => '155724'],
                        ],
                    ],
                ]);
                $conditionalStyles[] = new \PhpOffice\PhpSpreadsheet\Style\Conditional([
                    'condition' => 'E2="Pending Onsite"',
                    'style' => [
                        'fill' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFF3CD'],
                        'font' => [
                            'color' => ['rgb' => '856404'],
                        ],
                    ],
                ]);
                $sheet->setConditionalStyles('E2:E' . $lastRow, $conditionalStyles);

                // Add instructions as comments
                $sheet->getComment('F2')->getText()->createTextRun('Enter OR number if paying onsite');
                $sheet->getComment('G2')->getText()->createTextRun('Select Present or Absent from dropdown');

                // Auto-size columns (though we set specific widths, this ensures proper display)
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(false);
                }
            },
        ];
    }

    /**
     * Define validation rules for the Excel file.
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:Present,Absent',
            'or_number' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            'status.required' => 'Status is required',
            'status.in' => 'Status must be either Present or Absent',
            'or_number.max' => 'OR Number cannot exceed 255 characters',
        ];
    }
}
