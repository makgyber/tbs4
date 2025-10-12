<?php

namespace App\Traits;

use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

trait HasExporter
{

    protected function getTableBulkActions(): array
    {
        return [
            ExportBulkAction::make()->exports([
                ExcelExport::make()
                    ->askForFilename('contracts' . '-' . date('Ymd'))
            ])

        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            ExportAction::make()->exports([
                ExcelExport::make()
                    ->askForFilename('contracts' . '-' . date('Ymd'))
            ])
        ];
    }
}
