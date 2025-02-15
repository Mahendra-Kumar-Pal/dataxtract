<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Lead;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;

class LeadsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            return null;
        }

        if ($row[0] === 'Sr no.' || strtolower($row[1]) === 'date') {
            return null;
        }

        $date = null;
        try {
            if (is_numeric($row[1])) {
                $date = Carbon::instance(Date::excelToDateTimeObject($row[1]))->format('Y-m-d');
            } else {
                $date = Carbon::createFromFormat('d/m/Y', trim($row[1]))->format('Y-m-d');
            }
        } catch (\Exception $e) {
            $date = null;
        }
        if (!$date) {
            return null;
        }
        
        return new Lead([
            'date' => $date,
            'name' => $row[2],
            'mobile_no' => $row[3],
            'city' => $row[4],
            'source' => $row[5],
            'disposition' => $row[6],
            'lead_type' => $row[7] ?? null,
            'attempted' => $row[8],
            'remark' => $row[9] ?? null
        ]);
    }
}
