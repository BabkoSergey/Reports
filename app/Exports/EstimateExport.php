<?php

namespace App\Exports;

use App\Estimate;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EstimateExport implements FromView, WithEvents
{    
    public $id;
    
    public function __construct(int $id)
    {
        $this->id = $id;
    }
        
    public function view(): View
    {
        return view('admin.estimate.xls', [
            'estimate' => Estimate::find($this->id)
        ]);
    }
    
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '0056b3'],
                        ]
                    ]
                ];
                
                $event->sheet->getDelegate()->getRowDimension(3)->setRowHeight(20);
                
                $event->sheet->getDelegate()->getStyle('A3:E3');
                $event->sheet->getDelegate()->getStyle('A3:E'.$event->sheet->getHighestRow())->applyFromArray($styleArray);
            
            
                $event->sheet->getDelegate()->getStyle('A3:E'.$event->sheet->getHighestRow())
                    ->getAlignment()->setWrapText(true);
            },
        ];
    }
}
