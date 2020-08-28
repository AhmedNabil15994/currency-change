<?php
use App\Models\Variable;
class ConvertCurrency {
    
    static function convert($base,$to) {  
        $key = Variable::getVar('CONVERT_KEY');
        $url = 'https://openexchangerates.org/api/latest.json?base='.$base.'&app_id='.$key.'&symbols='.$to;            
        $header = array("content-type: application/json");    

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);    
        // close handle to release resources
        curl_close($ch);
        $result = json_decode($result);
        return isset($result->rates) ? round($result->rates->$to ,4): 1;
    }

    static function convertHistorical($base,$to,$date) {  
        $key = Variable::getVar('CONVERT_KEY');
        $url = "https://openexchangerates.org/api/historical/".$date.".json?base=".$base."&app_id=".$key."&symbols=".$to;            
        $header = array("content-type: application/json");    

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);    
        // close handle to release resources
        curl_close($ch);
        $result = json_decode($result);
        while(!isset($result->rates)){
            $newDate = date('Y-m-d',strtotime($date.' -1 day'));
            $key = Variable::getVar('CONVERT_KEY');
            $url = "https://openexchangerates.org/api/historical/".$newDate.".json?base=".$base."&app_id=".$key."&symbols=".$to;            
            $header = array("content-type: application/json");    

            $ch = curl_init();
            $timeout = 120;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            // Get URL content
            $result = curl_exec($ch);    
            // close handle to release resources
            curl_close($ch);
            $result = json_decode($result);
        }
        return isset($result->rates) ? round($result->rates->$to ,4) : 1;
    }

    
}
