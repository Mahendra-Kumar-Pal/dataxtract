<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LeadsExport implements FromCollection, WithHeadings
{
    protected $leads;

    public function __construct($leads)
    {
        $this->leads = $leads;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->leads)->map(function ($lead, $index) {
            return [
                'Sr no.' => $index + 1,
                'Date' => Carbon::parse($lead->date)->format('d/m/Y'),
                'Name' => $lead->name,
                'Mobile No' => $lead->mobile_no,
                'City' => $lead->city,
                'Source' => $lead->source,
                'Disposition' => $lead->disposition,
                'Lead Type' => $lead->lead_type,
                'Attempted' => (string) $lead->attempted,
                'Remark' => $lead->remark
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr no.',
            'Date',
            'Name',
            'Mobile No',
            'City',
            'Source',
            'Disposition',
            'Lead Type',
            'Attempted',
            'Remark'
        ];
    }
}
