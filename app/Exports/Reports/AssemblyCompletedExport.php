<?php

namespace App\Exports\Reports;

use App\Models\AssemblyWorkSession;
use App\Services\Reports\AssemblyReportService;
use App\Support\DurationHelper;
use Carbon\Carbon;
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

class AssemblyCompletedExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomStartCell,
    WithEvents,
    WithTitle
{
    public function __construct(
        private readonly ?string $startDate = null,
        private readonly ?string $endDate = null,
        private readonly ?string $search = null,
        private readonly ?string $worker = null,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | DATOS
    |--------------------------------------------------------------------------
    */

    public function collection(): Collection
    {
        return app(
            AssemblyReportService::class
        )
            ->completedQuery(
                startDate: $this->startDate,
                endDate: $this->endDate,
                search: $this->search,
                worker: $this->worker,
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | LA TABLA COMIENZA EN FILA 9
    |--------------------------------------------------------------------------
    */

    public function startCell(): string
    {
        return 'A9';
    }


    /*
    |--------------------------------------------------------------------------
    | ENCABEZADOS
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        return [
            'ESTATUS',
            'ARMADOR',
            'FECHA EN QUE TERMINÓ',
            'MARCA',
            'MODELO',
            'VIN',
            'TIEMPO DE ARMADO',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FILAS
    |--------------------------------------------------------------------------
    */

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


        $completedAt =
            $session->completed_at
            ? $session
                ->completed_at
                ->copy()
                ->timezone(
                    $this->displayTimezone()
                )
                ->format(
                    'd/m/Y H:i'
                )
            : '—';


        return [
            'Armado',

            $service->workerName(
                $session
            ),

            $completedAt,

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

            /*
             * Excel maneja tiempos como
             * fracciones de un día.
             */
            $effectiveSeconds / 86400,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATOS
    |--------------------------------------------------------------------------
    */

    public function columnFormats(): array
    {
        return [
            'F' =>
                NumberFormat::FORMAT_TEXT,

            'G' =>
                '[h]:mm:ss',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ESTILOS / CABECERA CORPORATIVA
    |--------------------------------------------------------------------------
    */

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
                        $service->completedSummary(
                            startDate:
                            $this->startDate,

                            endDate:
                            $this->endDate,

                            search:
                            $this->search,

                            worker:
                            $this->worker,
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | TÍTULOS
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A1:G1'
                    );

                    $sheet->setCellValue(
                        'A1',
                        'GRUPO RISE · CONTROL DE UNIDADES CEDIS'
                    );


                    $sheet->mergeCells(
                        'A2:G2'
                    );

                    $sheet->setCellValue(
                        'A2',
                        'R001 · ARMADOS FINALIZADOS'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | PERIODO
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A3:G3'
                    );

                    $sheet->setCellValue(
                        'A3',
                        'Periodo: '
                        . $this->formattedDate(
                            $this->startDate
                        )
                        . ' al '
                        . $this->formattedDate(
                            $this->endDate
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FILTROS
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A4:G4'
                    );

                    $sheet->setCellValue(
                        'A4',
                        'Armador: '
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
                    | GENERADO
                    |--------------------------------------------------------------------------
                    */

                    $sheet->mergeCells(
                        'A5:G5'
                    );

                    $sheet->setCellValue(
                        'A5',
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
                        'A7:B7'
                    );

                    $sheet->setCellValue(
                        'A7',
                        'Armados: '
                        . $summary['total']
                    );


                    $sheet->mergeCells(
                        'C7:D7'
                    );

                    $sheet->setCellValue(
                        'C7',
                        'Tiempo total: '
                        . DurationHelper::format(
                            $summary[
                                'total_seconds'
                            ]
                        )
                    );


                    $sheet->mergeCells(
                        'E7:F7'
                    );

                    $sheet->setCellValue(
                        'E7',
                        'Promedio: '
                        . DurationHelper::format(
                            $summary[
                                'average_seconds'
                            ]
                        )
                    );


                    $sheet->setCellValue(
                        'G7',
                        'Armadores: '
                        . $summary['workers']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | CABECERA OSCURA
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A1:G1'
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
                            'A1:G1'
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
                        ->getStyle(
                            'A1:G1'
                        )
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
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
                    | TÍTULO R001
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A2:G2'
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
                            'A3:G5'
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
                    | KPIs ÁMBAR
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A7:G7'
                        )
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        )
                        ->getStartColor()
                        ->setARGB(
                            'FFFFFBEB'
                        );


                    $sheet
                        ->getStyle(
                            'A7:G7'
                        )
                        ->getFont()
                        ->setBold(
                            true
                        )
                        ->setColor(
                            new \PhpOffice\PhpSpreadsheet\Style\Color(
                                'FF92400E'
                            )
                        );


                    $sheet
                        ->getStyle(
                            'A7:G7'
                        )
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        );


                    $sheet
                        ->getRowDimension(
                            7
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
                            'A9:G9'
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
                            'A9:G9'
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
                        ->getStyle(
                            'A9:G9'
                        )
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        );


                    $sheet
                        ->getRowDimension(
                            9
                        )
                        ->setRowHeight(
                            24
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | REGISTROS
                    |--------------------------------------------------------------------------
                    */

                    $totalRows =
                        $summary['total'];


                    $lastDataRow =
                        9 + $totalRows;


                    if (
                        $lastDataRow >= 10
                    ) {

                        $sheet
                            ->getStyle(
                                "A10:G{$lastDataRow}"
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


                        $sheet
                            ->getStyle(
                                "A10:G{$lastDataRow}"
                            )
                            ->getAlignment()
                            ->setVertical(
                                Alignment::VERTICAL_CENTER
                            );


                        /*
                         * Alternar filas.
                         */
                        for (
                            $row = 10;
                            $row <= $lastDataRow;
                            $row++
                        ) {

                            if (
                                $row % 2 === 0
                            ) {

                                $sheet
                                    ->getStyle(
                                        "A{$row}:G{$row}"
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
                    | FILTROS DE EXCEL
                    |--------------------------------------------------------------------------
                    */

                    $sheet->setAutoFilter(
                        "A9:G{$lastDataRow}"
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ANCHOS
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getColumnDimension('A')
                        ->setWidth(15);

                    $sheet
                        ->getColumnDimension('B')
                        ->setWidth(26);

                    $sheet
                        ->getColumnDimension('C')
                        ->setWidth(23);

                    $sheet
                        ->getColumnDimension('D')
                        ->setWidth(18);

                    $sheet
                        ->getColumnDimension('E')
                        ->setWidth(32);

                    $sheet
                        ->getColumnDimension('F')
                        ->setWidth(25);

                    $sheet
                        ->getColumnDimension('G')
                        ->setWidth(20);


                    /*
                    |--------------------------------------------------------------------------
                    | NOTA
                    |--------------------------------------------------------------------------
                    */

                    $noteRow =
                        $lastDataRow + 2;


                    $sheet->mergeCells(
                        "A{$noteRow}:G{$noteRow}"
                    );


                    $sheet->setCellValue(
                        "A{$noteRow}",
                        'Nota: El tiempo de armado corresponde al tiempo efectivo y excluye los periodos de pausa.'
                    );


                    $sheet
                        ->getStyle(
                            "A{$noteRow}:G{$noteRow}"
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
                            "A1:G{$noteRow}"
                        );
                },
        ];
    }


    public function title(): string
    {
        return 'Armados finalizados';
    }


    private function displayTimezone(): string
    {
        return config(
            'cedis.display_timezone',
            'America/Monterrey'
        );
    }


    private function formattedDate(
        ?string $date
    ): string {

        if (!$date) {
            return '—';
        }

        return Carbon::parse(
            $date
        )
            ->format(
                'd/m/Y'
            );
    }
}