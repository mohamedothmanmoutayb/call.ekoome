<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Http\Request;

class AllLead implements FromCollection, WithHeadings
{
    use Exportable;


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
            $lead = Lead::join('products','products.id','leads.id_product')->where('status_confirmation','confirmed')->where('status_livrison','delivered')->select('n_lead AS Nlead','leads.name AS Customer','products.name AS Product','leads.lead_value AS Prices','city AS City','leads.created_at AS Created')->get();
       
        
       //dd($lead);
        return $lead;
    }

    
    public function headings(): array
    {
        return [
            'N Lead',
            'Customer Name',
            'Product Name',
            'Price',
            'City',
            'Created At',
        ];
    }
}
