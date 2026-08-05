<?php

namespace App\Exports;

use App\Exports\Sheets\NeracaSheet;
use App\Exports\Sheets\PemasukanSheet;
use App\Exports\Sheets\PengeluaranSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganExport implements WithMultipleSheets
{
    protected $filter;
    protected $dari;
    protected $sampai;

    public function __construct($filter = null, $dari = null, $sampai = null)
    {
        $this->filter = $filter ?? 'bulanan';
        $this->dari = $dari;
        $this->sampai = $sampai;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new NeracaSheet($this->filter, $this->dari, $this->sampai),
            new PemasukanSheet($this->filter, $this->dari, $this->sampai),
            new PengeluaranSheet($this->filter, $this->dari, $this->sampai),
        ];
    }
}
