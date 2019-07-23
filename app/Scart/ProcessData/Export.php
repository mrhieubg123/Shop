<?php

namespace App\Scart\ProcessData;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Export
{
    public $filename;
    public $sheetname;
    public $title;

    public function __construct()
    {
        $this->filename  = 'File export';
        $this->sheetname = 'Sheet name';
        $this->title     = '';
    }

    public function exportExcel($dataExport = [], $options = [])
    {
        $filename    = $options['filename'] ?? $this->filename;
        $sheetname   = $options['sheetname'] ?? $this->sheetname;
        $title       = $options['title'] ?? $this->title;
        $row         = empty($title) ? 1 : 2;
        $spreadsheet = new Spreadsheet();
        $worksheet   = $spreadsheet->getActiveSheet();
        $worksheet->setTitle($this->sheetname);
        if ($row == 2) {
            $worksheet->getCell('A1')->setValue($title);
        }
        $worksheet->fromArray($dataExport, $nullValue = null, $startCell = 'A' . $row);
        $writer = IOFactory::createWriter($spreadsheet, "Xls");
        // $writer->save('write.xls');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer->save("php://output");
    }

}
