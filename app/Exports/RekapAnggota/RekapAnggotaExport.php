<?php

namespace App\Exports\RekapAnggota;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapAnggotaExport implements FromView
{
    public function view(): View
    {
        return view('exports.invoices', [
            'invoices' => Invoice::all()
        ]);
    }
}
