<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Event\Entities\EventAttendees;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportAttendees implements FromView, WithColumnWidths, ShouldAutoSize
{
    public $event;

    public function __construct($event)
    {
        $this->event = $event;    
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $data = $this->event;
        return view('exports.event.attendees', compact('data'));
    }

    public function columnWidths(): array
    {
        return [
            'D' => 25,           
        ];
    }

    public function getDrawings()
    {
        $drawings = [];
        foreach ($this->event as $key => $item) {
            if ($item->signature_path) {
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath($item->signature_path);
                $drawing->setHeight(50);
                $drawing->setCoordinates('D' . $key);
                $drawings[] = $drawing;
            }
        }

        return $drawings;

    }

    // public function setImage($workSheet) {
    //     foreach ($this->event as $key => $item) {
    //         if ($item->signature_path) {
    //             $drawing = new Drawing();
    //             $drawing->setName('signature');
    //             $drawing->setPath(public_path() . '/export/attendeed/image/63d97d8044cb3.png');
    //             $drawing->setHeight(40);
    //             $drawing->setCoordinates("D$key");
    //             $drawing->setWorksheet($workSheet);
    //         }
    //     }
    // }

    // public function registerEvents():array {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $event->sheet->getDefaultRowDimension()->setRowHeight(60);
    //             $workSheet = $event->sheet->getDelegate();
    //             $this->setImage($workSheet);
    //         },
    //     ];
    // }
}
