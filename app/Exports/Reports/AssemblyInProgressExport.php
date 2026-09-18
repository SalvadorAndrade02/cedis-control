<?php

namespace App\Exports\Reports;

use App\Enums\AssemblyWorkStatus;
use App\Models\AssemblyWorkSession;
use App\Services\Reports\AssemblyReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class AssemblyInProgressExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomStartCell,
    WithEvents,
    WithTitle
{
    public function __construct(
        private readonly ?string $search = null,
        private readonly ?AssemblyWorkStatus $status = null,
        private readonly ?string $worker = null,
    ) {
    }


    public function collection(): Collection
    {
        return app(
            AssemblyReportService::class
        )
            ->inProgressQuery(
                search:
                $this->search,

                status:
                $this->status,

                worker:
                $this->worker,
            )
            ->get();
    }


    public function startCell(): string
    {
        return 'A8';
    }


    public function headings(): array
    {
        return [
            'ESTATUS',
            'ARMADOR',
            'MARCA',
            'MODELO',
            'VIN',
            'HORAS INVERTIDAS',
        ];
    }


    public function map(
        $session
    ): array {

        /** @var AssemblyWorkSession $session */

        $service = app(
            AssemblyReportService::class
        );


        $effectiveSeconds =
            $service->effectiveSeconds(
                $session
            );


        $statusLabel = match (
        $session->status
        ) {

            AssemblyWorkStatus::RUNNING =>
                'Proceso',

            AssemblyWorkStatus::PAUSED =>
                'Pausado',

            default =>
                $session->status->label(),
        };


        return [
            $statusLabel,

            $service->workerName(
                $session
            ),

            $session
                ->unit
                ?->brand
                    ?->name
            ?? '—',

            $session
                ->unit
                    ?->model
            ?? '—',

            $session
                ->unit
                    ?->vin
            ?? '—',

            $effectiveSeconds / 86400,
        ];
    }


    public function columnFormats(): array
    {
        return [
            'E' =>
                NumberFormat::FORMAT_TEXT,

            'F' =>
                '[h]:mm:ss',
        ];
    }


    public function registerEvents(): array
    {
        return [

            AfterSheet::class =>
                function (AfterSheet $event) {

                    $sheet =
                        $event
                            ->sheet
                            ->getDelegate();


                    $service = app(
                        AssemblyReportService::class
                    );


                    $summary =
                        $service
                            ->inProgressSummary();


                    $rows =
                        $service
                            ->inProgressQuery(
                                search:
                                $this->search,

                                status:
                                $this->status,

                                worker:
                                $this->worker,
                            )
                            ->count();


                    /*
                    |--------------------------------------------------------------------------
                    | IDENTIDAD
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A1:F1'
                    );

                    $sheet->setCellValue(
                        'A1',
                        'GRUPO RISE · CONTROL DE UNIDADES CEDIS'
                    );


                    $sheet->mergeCells(
                        'A2:F2'
                    );

                    $sheet->setCellValue(
                        'A2',
                        'R002 · ARMADOS EN PROCESO'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FILTROS
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A3:F3'
                    );

                    $sheet->setCellValue(
                        'A3',
                        'Estado: '
                        . (
                            $this->status
                            ? $this->status->label()
                            : 'Todos'
                        )
                        . '   |   Armador: '
                        . (
                            $this->worker
                            ?: 'Todos'
                        )
                        . '   |   Búsqueda: '
                        . (
                            $this->search
                            ?: 'Sin filtro'
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FECHA GENERACIÓN
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A4:F4'
                    );

                    $sheet->setCellValue(
                        'A4',
                        'Generado: '
                        . now(
                            $this->displayTimezone()
                        )
                            ->format(
                                'd/m/Y H:i:s'
                            )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | KPIs
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A6:B6'
                    );

                    $sheet->setCellValue(
                        'A6',
                        'En armado: '
                        . $summary['running']
                    );


                    $sheet->mergeCells(
                        'C6:D6'
                    );

                    $sheet->setCellValue(
                        'C6',
                        'Pausados: '
                        . $summary['paused']
                    );


                    $sheet->mergeCells(
                        'E6:F6'
                    );

                    $sheet->setCellValue(
                        'E6',
                        'Total en proceso: '
                        . $summary['total']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | HEADER CORPORATIVO
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A1:F1'
                        )
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        )
                        ->getStartColor()
                        ->setARGB(
                            'FF0B1220'
                        );


                    $sheet
                        ->getStyle(
                            'A1:F1'
                        )
                        ->getFont()
                        ->setBold(
                            true
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FFFFFFFF'
                            )
                        )
                        ->setSize(
                            12
                        );


                    $sheet
                        ->getRowDimension(
                            1
                        )
                        ->setRowHeight(
                            28
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | TÍTULO
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A2:F2'
                        )
                        ->getFont()
                        ->setBold(
                            true
                        )
                        ->setSize(
                            17
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FF0F172A'
                            )
                        );


                    $sheet
                        ->getRowDimension(
                            2
                        )
                        ->setRowHeight(
                            30
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | INFORMACIÓN
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A3:F4'
                        )
                        ->getFont()
                        ->setSize(
                            10
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FF64748B'
                            )
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | KPI AZUL
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A6:F6'
                        )
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        )
                        ->getStartColor()
                        ->setARGB(
                            'FFEFF6FF'
                        );


                    $sheet
                        ->getStyle(
                            'A6:F6'
                        )
                        ->getFont()
                        ->setBold(
                            true
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FF1D4ED8'
                            )
                        );


                    $sheet
                        ->getRowDimension(
                            6
                        )
                        ->setRowHeight(
                            25
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | HEADER TABLA
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A8:F8'
                        )
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        )
                        ->getStartColor()
                        ->setARGB(
                            'FF111827'
                        );


                    $sheet
                        ->getStyle(
                            'A8:F8'
                        )
                        ->getFont()
                        ->setBold(
                            true
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FFFFFFFF'
                            )
                        );


                    $sheet
                        ->getRowDimension(
                            8
                        )
                        ->setRowHeight(
                            24
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | DATOS
                    |--------------------------------------------------------------------------
                    */

                    $lastDataRow =
                        8 + $rows;


                    if (
                        $lastDataRow >= 9
                    ) {

                        $sheet
                            ->getStyle(
                                "A9:F{$lastDataRow}"
                            )
                            ->getBorders()
                            ->getBottom()
                            ->setBorderStyle(
                                Border::BORDER_HAIR
                            )
                            ->getColor()
                            ->setARGB(
                                'FFE2E8F0'
                            );


                        /*
                         * Colorear estado.
                         */
                        for (
                            $row = 9;
                            $row <= $lastDataRow;
                            $row++
                        ) {

                            $status =
                                $sheet
                                    ->getCell(
                                        "A{$row}"
                                    )
                                    ->getValue();


                            if (
                                $status === 'Pausado'
                            ) {

                                $sheet
                                    ->getStyle(
                                        "A{$row}"
                                    )
                                    ->getFont()
                                    ->setBold(true)
                                    ->setColor(
                                        new \PhpOffice\PhpSpreadsheet\Style\Color(
                                            'FFB45309'
                                        )
                                    );

                            } else {

                                $sheet
                                    ->getStyle(
                                        "A{$row}"
                                    )
                                    ->getFont()
                                    ->setBold(true)
                                    ->setColor(
                                        new \PhpOffice\PhpSpreadsheet\Style\Color(
                                            'FF2563EB'
                                        )
                                    );
                            }


                            if (
                                $row % 2 === 1
                            ) {

                                $sheet
                                    ->getStyle(
                                        "A{$row}:F{$row}"
                                    )
                                    ->getFill()
                                    ->setFillType(
                                        Fill::FILL_SOLID
                                    )
                                    ->getStartColor()
                                    ->setARGB(
                                        'FFF8FAFC'
                                    );
                            }
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AUTOFILTER
                    |--------------------------------------------------------------------------
                    */

                    $sheet->setAutoFilter(
                        "A8:F{$lastDataRow}"
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ANCHOS
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getColumnDimension('A')
                        ->setWidth(16);

                    $sheet
                        ->getColumnDimension('B')
                        ->setWidth(27);

                    $sheet
                        ->getColumnDimension('C')
                        ->setWidth(18);

                    $sheet
                        ->getColumnDimension('D')
                        ->setWidth(32);

                    $sheet
                        ->getColumnDimension('E')
                        ->setWidth(25);

                    $sheet
                        ->getColumnDimension('F')
                        ->setWidth(20);


                    /*
                    |--------------------------------------------------------------------------
                    | NOTA
                    |--------------------------------------------------------------------------
                    */

                    $noteRow =
                        $lastDataRow + 2;


                    $sheet->mergeCells(
                        "A{$noteRow}:F{$noteRow}"
                    );


                    $sheet->setCellValue(
                        "A{$noteRow}",
                        'Nota: Este reporte representa el estado de los armados al momento de su generación. El tiempo efectivo excluye las pausas.'
                    );


                    $sheet
                        ->getStyle(
                            "A{$noteRow}:F{$noteRow}"
                        )
                        ->getFont()
                        ->setItalic(
                            true
                        )
                        ->setSize(
                            9
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FF64748B'
                            )
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | IMPRESIÓN
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getPageSetup()
                        ->setOrientation(
                            PageSetup::ORIENTATION_LANDSCAPE
                        )
                        ->setPaperSize(
                            PageSetup::PAPERSIZE_A4
                        )
                        ->setFitToWidth(
                            1
                        )
                        ->setFitToHeight(
                            0
                        );


                    $sheet
                        ->getPageSetup()
                        ->setPrintArea(
                            "A1:F{$noteRow}"
                        );
                },
        ];
    }


    public function title(): string
    {
        return 'Armados en proceso';
    }


    private function displayTimezone(): string
    {
        return config(
            'cedis.display_timezone',
            'America/Monterrey'
        );
    }
}