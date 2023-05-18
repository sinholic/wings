<?php

namespace App\Http\Controllers;

use App\Models\TransactionHeader;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function sales(Request $request)
    {
        $datas = TransactionHeader::with('details');
        if ($request->date != '' && $request->date != 'All') {
            $date     =   explode("-",$request->date);
            $datas->whereRaw("YEAR(date) = $date[0] AND MONTH(date) = $date[1]");;
        }
        $datas = $datas->get();
        $contents = array(
            array(
                'field' => 'document_code',
                'label' => 'Document Code'
            ),
            array(
                'field' => 'document_number',
                'label' => 'Document Number'
            ),
            array(
                'field' => 'username',
                'label' => 'Username'
            ),
            array(
                'field' => 'total',
                'label' => 'Total'
            ),
            array(
                'field' => 'date',
                'label' => 'Date'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
        );
        $filters            =   array(
            array(
                'field'     =>  'date',
                'label'     =>  'Invoiced at',
                'type'      =>  'filter_month_year',
                'value'     =>  $request->date
            )
        );
        return view('page.content.index')
        ->with('filters', $filters) 
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
    }
}
