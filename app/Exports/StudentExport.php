<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Allocation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;

class StudentExport implements FromCollection, WithHeadings, WithCustomStartCell, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    protected $students;
    protected $allocation;
    protected $start;

    public function __construct($students, Allocation $allocation)
    {
        $this->students = $students;
        $this->allocation = $allocation;
        $this->start = 7;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'Admission Number',
            'Name',
            'Gender',
            'Attendance',
        ];
    }

    public function startCell(): string
    {
        return 'A' . $this->start;
    }

    public function columnFormats(): array
    {
        return [
            'D' => '@', // Format column D (Attendance) as text
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Hide row 1
                // $event->sheet->getRowDimension(1)->setVisible(false);

                // Add allocation ID to cell A1
                $event->sheet->setCellValue('A1', 'Allocation ID: ');
                $event->sheet->setCellValue('B1', $this->allocation->id);
                $event->sheet->setCellValue('A2', "Instructor Name:");
                $event->sheet->setCellValue('B2', $this->allocation->staff->name);
                $event->sheet->setCellValue('A3', "Intakes:");
                $event->sheet->getStyle('B3')->getAlignment()->setWrapText(true);
                $event->sheet->getRowDimension(3)->setRowHeight(-1);
                $event->sheet->getColumnDimension('B')->setWidth(2 * 7);
                $event->sheet->setCellValue('B3', implode(",", $this->allocation->intakes->pluck('name')->toArray()));
                $event->sheet->setCellValue('A4', "Subject Name:");
                $event->sheet->setCellValue('B4', $this->allocation->subject->name);

                // Insert checkboxes in the Attendance column starting from row 2
                $lastRow = count($this->students) + $this->start + 1; // Add 1 to include the heading row
                // $event->sheet->getColumnDimension('D')->setVisible(false); // Hide the column to show only checkboxes

                // Loop through each row to insert checkboxes
                // for ($row = $this->start; $row <= $lastRow; $row++) {
                //     $cell = 'D' . $row;
                //     $event->sheet->getCell($cell)->setValue(''); // Set an empty value
                //     $event->sheet->getCell($cell)->getStyle()->getAlignment()->setHorizontal('center'); // Center align the checkbox
                //     $event->sheet->getCell($cell)->getStyle()->getAlignment()->setVertical('center'); // Center align the checkbox
                //     $event->sheet->getCell($cell)->getStyle()->getAlignment()->setWrapText(true); // Wrap text to fit the cell
                //     $event->sheet->getCell($cell)->getStyle()->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED); // Unlock the cell
                //     $event->sheet->getCell($cell)->getStyle()->getFont()->setSize(10); // Set font size

                //     $event->sheet->getCell($cell)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add border

                //     // Set data validation to accept only 1 or 0
                //     $event->sheet->getCell($cell)->getDataValidation()->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
                //     $event->sheet->getCell($cell)->getDataValidation()->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                //     $event->sheet->getCell($cell)->getDataValidation()->setShowInputMessage(true);
                //     $event->sheet->getCell($cell)->getDataValidation()->setShowErrorMessage(true);
                //     $event->sheet->getCell($cell)->getDataValidation()->setAllowBlank(false);
                //     $event->sheet->getCell($cell)->getDataValidation()->setShowDropDown(true);
                //     $event->sheet->getCell($cell)->getDataValidation()->setErrorTitle('Input error');
                //     $event->sheet->getCell($cell)->getDataValidation()->setError('Only 1 or 0 is allowed');
                //     $event->sheet->getCell($cell)->getDataValidation()->setPromptTitle('Pick a value');
                //     $event->sheet->getCell($cell)->getDataValidation()->setPrompt('Only 1 or 0 is allowed');

                //     // Set formula to convert checkbox to 1 or 0
                //     $event->sheet->getCell($cell)->setValue('=IF(D' . $row . ', 1, 0)');
                // }
            },
        ];
    }
}
