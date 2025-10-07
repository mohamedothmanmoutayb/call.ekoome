<?php

use App\Models\HistoryStatu;


if (!function_exists('dateConvert')) {

    function dateConvert($input_date)
    { 
       
            return \Carbon\Carbon::parse($input_date)->format('Y/m/d');
            //  \Carbon\Carbon::parse($input_date)->format($system_date_format);
       
    }

  
}
 function generateUniqueCode()
{
    do {
        $code = random_int(42092, 52092); 
    } while (HistoryStatu::where("id_lead", "=", $code)->first());
    return $code;
}