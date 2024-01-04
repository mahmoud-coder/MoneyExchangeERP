<?php
namespace App\Excel\Exports;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Option;

class GeneralJournalsExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $entries;

    public function __construct($entries, $language = 'en')
    {
        $this->entries = $entries;
        $this->language = $language;
    }

    public function view(): view
    {
        return view('admin.accounting.entries_excel', [
            'entries' => $this->entries,
            'lang' => $this->language,
        ]);
    }

}