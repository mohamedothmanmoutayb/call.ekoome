<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportStaff implements FromCollection , WithHeadings
{
    use Exportable;

    protected $id;
    public function __construct(array $id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        foreach($this->id as  $v_id){
            $id = $v_id;
            $user = User::join('leads','leads.id_assigned','users.id')->whereIn('users.id',$v_id)->select('users.name as name','users.telephone as telephone','users.email as email',\DB::raw('count(leads.id) as total'))->groupby('users.id','leads.id_assigned','users.name','users.telephone','users.email')->get();
        }
        //dd($user);
        return $user;
    }

    
    public function headings(): array
    {
        return [
            'Agent Name',
            'Agent Phone',
            'Agent Email',
            'Total Leads',
        ];
    }
}
