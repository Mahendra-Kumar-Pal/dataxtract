<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lead;
use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        //------Fetch Distinct Values for Filters------
        $cities = Lead::whereNotNull('city')->distinct()->pluck('city');
        $sources = Lead::whereNotNull('source')->distinct()->pluck('source');
        $leadTypes = Lead::whereNotNull('lead_type')->distinct()->pluck('lead_type');

        if ($request->ajax()) {
            try {    
                //------Apply Filters Before Fetching Data------
                $query = Lead::select('id', 'date', 'name', 'mobile_no', 'city', 'source', 'disposition', 'lead_type', 'attempted', 'remark');

                if ($request->filled('city')) {
                    $query->where('city', $request->city);
                }
                if ($request->filled('source')) {
                    $query->where('source', $request->source);
                }
                if ($request->filled('lead_type')) {
                    $query->where('lead_type', $request->lead_type);
                }

                //-------Fetch Data from Database------
                $data = $query->get()->map(function ($lead) {
                    return [
                        'id' => $lead->id,
                        'date' => Carbon::parse($lead->date)->format('d/m/Y'),
                        'name' => $lead->name,
                        'mobile_no' => $lead->mobile_no,
                        'city' => $lead->city,
                        'source' => $lead->source,
                        'disposition' => $lead->disposition,
                        'lead_type' => $lead->lead_type,
                        'attempted' => (string) $lead->attempted,
                        'remark' => $lead->remark,
                    ];
                });

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        return view('leads.index', compact('cities', 'sources', 'leadTypes'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ], [
            'file.required' => 'Please upload an Excel file.',
            'file.mimes' => 'The uploaded file must be in XLSX format.'
        ]);

        Excel::import(new LeadsImport, $request->file('file'));

        return response()->json(['success' => 'Leads Imported Successfully']);
    }

    public function export(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        // $fileName = 'leads_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        // return Excel::download(new LeadsExport, $fileName);
        $query = Lead::query();

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }

        $leads = $query->select('id', 'date', 'name', 'mobile_no', 'city', 'source', 'disposition', 'lead_type', 'attempted', 'remark')->get();

        $fileName = 'leads_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new LeadsExport($leads), $fileName);
    }
}
