<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Http\Request;

class LeadExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $leads;
    public function __construct(array $leads)
    {
        $this->leads = $leads;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        foreach($this->leads as $v_lead){
            $lead = Lead::join('lead_products','lead_products.id_lead','leads.id')->join('products','products.id','lead_products.id_product')->whereIn('leads.id',$v_lead)->select('n_lead AS Nlead','leads.name AS Customer','products.name AS Product','leads.note As Note','lead_products.lead_value AS Prices','city AS City','Address','phone AS Phone1','phone2 AS Phone2','status_confirmation AS Confirmation','status_livrison AS Shipping','status_payment AS Payment','leads.date_delivred AS Date_Delivred','leads.created_at AS Created')->get();
        }
        
       //dd($lead);
        return $lead;
    }

    
    public function headings(): array
    {
        return [
            'N Lead',
            'Customer Name',
            'Product Name',
            'Note',
            'Price',
            'City',
            'Address',
            'Phone',
            'Phone 2',
            'Status Confiramtion',
            'Status Livraison',
            'Status Payment',
            'Date Shipping',
            'Created At',
        ];
    }
}
