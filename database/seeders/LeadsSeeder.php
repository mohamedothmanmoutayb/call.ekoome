<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\HistoryStatu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
  


    
    public function run()
    { 
        $array = ['confirmed', 'no answer', 'call later', 'canceled', 'Wrong','Duplicated','Delivered'];
        $arrayx = ['no answer', 'no answer 1 ', 'no answer 2', 'no answer 3',  'no answer 4', 'no answer 5','no answer 6','no answer 7','no answer 8'];
        $dt = Carbon::now(); 
        for ($i=0; $i < 300; $i++) { 
        HistoryStatu::create([   
            'id_lead' => generateUniqueCode(),
            'id_user' => 241,
            'status' => Arr::random($array),
            'date' => $dt->toDateTimeString()
        ]);
        } 
    }
}
