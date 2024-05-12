<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Cache;

function formatDate()
{
     return \Carbon\Carbon::parse(now())->format('Y-m-d');
}
 function cache($key,$time,$function){
    Cache::remember($key,$time,$function);
}

?>
