<?php
/*
* Контроллер админ версии нарядов
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Employee;
use App\Client;
use App\Cars_in_service;
use App\Assignment;
use App\Sub_assignment;
use App\Workzone;
use Telegram\Bot\Laravel\Facades\Telegram;

use App\Assignments_income;
use App\Assignments_expense;
use App\Assignments_completed_works;

use App\Zonal_assignments_income;
use App\Zonal_assignments_expense;
use App\Zonal_assignments_completed_works;

use DateTime;
use App\Exchange_rates;

use App\Month_profitability;
use App\Work_direction;
use App\New_sub_assignment;

use Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Style_NumberFormat;

class Assignments_Admin_Controller extends Controller
{
    
    /* Отображения списка всех нарядов */
    public function assignments_index(){
        /* Получаем всю нужную информацию по нарядам */
        $assignments_data =
            DB::table('assignments')
                ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->join('new_sub_assignments', 'assignments.id', '=', 'new_sub_assignments.assignment_id')
                ->orderBy('order','ASC')
                ->select(
                        'assignments.*',
                        'employees.general_name AS employee_name',
                        'cars_in_service.general_name AS car_name',
                        'cars_in_service.vin_number AS vin_number',
                        'cars_in_service.release_year AS release_year',
                        'cars_in_service.reg_number AS reg_number',
                        'cars_in_service.car_color AS car_color',
                        'new_sub_assignments.d_table_workzone AS assignment_workzone'
                    )
                ->where('new_sub_assignments.work_row_index', '<>', null)
                ->get();
        $workzone_data = DB::table('workzones')->get();

        /*echo '<pre>'.print_r($assignments_data,true).'</pre>';
        var_dump(empty($assignments_data));*/

        // Собираем зональные наряды в массив
        if($assignments_data->count()){
            $temp_arr_obj = [];
            $temp_arr_workzone = [];
            $temp_id = $assignments_data[0]->order;
            $i = 0;

            for ( ;$i < count($assignments_data); $i++) { 
                if ($temp_id == $assignments_data[$i]->order) {
                    $temp_arr_workzone[] = $assignments_data[$i]->assignment_workzone;
                }
                else{
                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    $temp_id = $assignments_data[$i]->order;
                    $temp_arr_workzone = [];
                    $temp_arr_workzone[] = $assignments_data[$i]->assignment_workzone;
                }                
            }

            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;

            $assignments_data = $temp_arr_obj;
        }
        //echo '<pre>'.print_r($assignments_data,true).'</pre>';
        return view('assignments_admin.assignments_admin_index', ['assignments' => $assignments_data, 'workzone_data' => $workzone_data]);
    }


    //Онлайн-переводчик
    public function gtranslate($str, $lang_from, $lang_to) {
        $query_data = array(
            'client' => 'x',
            'q' => $str,
            'sl' => $lang_from,
            'tl' => $lang_to
        );
        $filename = 'http://translate.google.ru/translate_a/t';
        $options = array(
            'http' => array(
              'user_agent' => 'Mozilla/5.0 (Windows NT 6.0; rv:26.0) Gecko/20100101 Firefox/26.0',
              'method' => 'POST',
              'header' => 'Content-type: application/x-www-form-urlencoded',
              'content' => http_build_query($query_data)
          )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($filename, false, $context);
        return json_decode($response);
    }


    // Excel
    // Генератор штрихкода
    public function barcode($text="0", $size="40", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor=1 ) {
        $code_string = "";
        // Translate the $text into barcode the correct $code_type
        if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211214" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code128a" ) {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211412" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code39" ) {
            $code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                $code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
            }

            $code_string = "1211212111" . $code_string . "121121211";
        } elseif ( strtolower($code_type) == "code25" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0");
            $code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

            for ( $X = 1; $X <= strlen($text); $X++ ) {
                for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
                    if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
                        $temp[$X] = $code_array2[$Y];
                }
            }

            for ( $X=1; $X<=strlen($text); $X+=2 ) {
                if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
                    $temp1 = explode( "-", $temp[$X] );
                    $temp2 = explode( "-", $temp[($X + 1)] );
                    for ( $Y = 0; $Y < count($temp1); $Y++ )
                        $code_string .= $temp1[$Y] . $temp2[$Y];
                }
            }

            $code_string = "1111" . $code_string . "311";
        } elseif ( strtolower($code_type) == "codabar" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
            $code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
                    if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
                        $code_string .= $code_array2[$Y] . "1";
                }
            }
            $code_string = "11221211" . $code_string . "1122121";
        }

        // Pad the edges of the barcode
        $code_length = 20;
        if ($print) {
            $text_height = 30;
        } else {
            $text_height = 0;
        }
        
        for ( $i=1; $i <= strlen($code_string); $i++ ){
            $code_length = $code_length + (integer)(substr($code_string,($i-1),1));
            }

        if ( strtolower($orientation) == "horizontal" ) {
            $img_width = $code_length*$SizeFactor;
            $img_height = $size;
        } else {
            $img_width = $size;
            $img_height = $code_length*$SizeFactor;
        }

        $image = imagecreate($img_width, $img_height + $text_height);
        $black = imagecolorallocate ($image, 0, 0, 0);
        $white = imagecolorallocate ($image, 255, 255, 255);

        imagefill( $image, 0, 0, $white );
        if ( $print ) {
            imagestring($image, 5, 31, $img_height, $text, $black );
        }

        $location = 10;
        for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
            $cur_size = $location + ( substr($code_string, ($position-1), 1) );
            if ( strtolower($orientation) == "horizontal" )
                imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
            else
                imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
            $location = $cur_size;
        }
        
        // Draw barcode to the screen or save in a file
            $image_name = "barcode_".$text.".png";
            imagepng($image,"excel/".$image_name);
            imagedestroy($image);
    }


    /**
    * Возвращает сумму прописью
    */
    public function num_to_str($n){
        $words=array(
            900=>'девятьсот',
            800=>'восемьсот',
            700=>'семьсот',
            600=>'шестьсот',
            500=>'пятьсот',
            400=>'четыреста',
            300=>'триста',
            200=>'двести',
            100=>'сто',
            90=>'девяносто',
            80=>'восемьдесят',
            70=>'семьдесят',
            60=>'шестьдесят',
            50=>'пятьдесят',
            40=>'сорок',
            30=>'тридцать',
            20=>'двадцать',
            19=>'девятнадцать',
            18=>'восемнадцать',
            17=>'семнадцать',
            16=>'шестнадцать',
            15=>'пятнадцать',
            14=>'четырнадцать',
            13=>'тринадцать',
            12=>'двенадцать',
            11=>'одиннадцать',
            10=>'десять',
            9=>'девять',
            8=>'восемь',
            7=>'семь',
            6=>'шесть',
            5=>'пять',
            4>'четыре',
            3=>'три',
            2=>'два',
            1=>'один',
        );

        $level=array(
            4=>array('миллиард', 'миллиарда', 'миллиардов'),
            3=>array('миллион', 'миллиона', 'миллионов'),
            2=>array('тысяча', 'тысячи', 'тысяч'),
        );

        list($rub,$kop)=explode('.',number_format($n,2));
        $parts=explode(',',$rub);

        for($str='', $l=count($parts), $i=0; $i<count($parts); $i++, $l--) {
            if (intval($num=$parts[$i])) {
                foreach($words as $key=>$value) {
                    if ($num>=$key) {
                    // Fix для одной тысячи
                        if ($l==2 && $key==1) {
                            $value='одна';
                        }
                    // Fix для двух тысяч
                        if ($l==2 && $key==2) {
                            $value='две';
                        }
                        $str.=($str!=''?' ':'').$value;
                        $num-=$key;
                    }
                }
                if (isset($level[$l])) {
                    $str.=' '.$this->num2word($parts[$i],$level[$l]);
                }
            }
        }

        if (intval($rub=str_replace(',','',$rub))) {
            $str.=' '.$this->num2word($rub,array('lei', 'lei', 'lei'));
        }

        $str.=($str!=''?' ':'').$kop;
        $str.=' '.$this->num2word($kop,array('bani', 'bani', 'bani'));

        return mb_strtoupper(mb_substr($str,0,1,'utf-8'),'utf-8').
        mb_substr($str,1,mb_strlen($str,'utf-8'),'utf-8'); 
    }
     
    /**
     * Склоняем словоформу
     */
    public function num2word($n,$words) {
        return ($words[($n=($n=$n%100)>19?($n%10):$n)==1?0 : (($n>1&&$n<=4)?1:2)]);
    } 

    //  Сумма прописью на румынском
    public function sum_translate($sum){
        $sum_arr = explode('.', $sum);
        $temp_arr = explode('lei', $this->num_to_str($sum_arr[0]));
        $text = $this->gtranslate($temp_arr[0], 'ru', 'ro');       
        if (!empty($sum_arr[1])) {
            $text = $text.' lei '.$sum_arr[1].' '.'bani';
        }
        else{
            $text = $text.' lei';
        }
        return $text;
    }

    
    // Генератор Excel
    public function exportExcel($doc_name, $assignment_id){

        $files = scandir('excel');
        foreach ($files as $value) {
            if(stripos($value, 'barcode') !== false){
                unlink("excel/".$value);
            }
        }

        $assignment_data =
            DB::table('assignments')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->join('new_sub_assignments', 'assignments.id', '=', 'new_sub_assignments.assignment_id')
                ->select(
                        'assignments.date_of_creation',
                        'cars_in_service.general_name AS car_name',
                        'cars_in_service.vin_number AS vin_number',
                        'cars_in_service.release_year AS release_year',
                        'cars_in_service.reg_number AS reg_number',
                        'cars_in_service.mileage_km AS mileage_km',
                        'new_sub_assignments.*'
                    )
                ->where('assignments.id', '=', $assignment_id)
                ->get();

        $car_data = DB::table('car_model_list')->select('brand', 'model')->where('general_name', '=', $assignment_data[0]->car_name)->get();
        $currency = DB::table('exchange_rates')->select('usd', 'eur')->get();
        if ($assignment_data->count() == 0 OR $car_data->count() == 0) {
            return 'Нет данных для файла !';
        }
        
        //Создаем массив для форматирования чисел в формат "0,00"
        $arr_format_for_payment = array('R24:AD24' => '0.00');
        $arr_format_work_order = array('R31:AD31' => '0.00');
        $arr_format_invoice = array('U31:AD31' => '0.00');
        $index = 0;
        
        switch ($doc_name) {
            case 'invoice_for_payment':                
                foreach ($arr_format_for_payment as $key => $value) {
                    $index = (int)substr($key, 1, 2);
                }
                break;
            
            case 'work_order':
                foreach ($arr_format_work_order as $key => $value) {
                    $index = (int)substr($key, 1, 2);
                }
                break;

            case 'invoice':
                foreach ($arr_format_invoice as $key => $value) {
                    $index = (int)substr($key, 1, 2);
                }
                break;
            
            default:                
                break;
        }
                    
        // Подсчитываем сумму в работах
        $sum_work = 0;
        foreach($assignment_data as $assignment){
            if(!empty($assignment->work_row_index)){
                $index++;
                $arr_format_for_payment['R'.$index.':AD'.$index] = '0.00';
                $arr_format_work_order['R'.$index.':AD'.$index] = '0.00';
                $arr_format_invoice['U'.$index.':AD'.$index] = '0.00';
                
                $coefficient = 1;
                if ($assignment->d_table_currency === 'USD') {
                    $coefficient = $currency[0]->usd;
                }
                elseif ($assignment->d_table_currency === 'EUR') {
                    $coefficient = $currency[0]->eur;
                }
                $sum_work += round(((int)$assignment->work_sum_row/$coefficient),2);
            }
        }
        
        $index += 4;
        
        // Подсчитываем сумму в запчастях
        $sum_spares = 0; 
        foreach($assignment_data as $assignment) {
            if(!empty($assignment->spares_row_index)) {
                $index++;
                $arr_format_for_payment['R'.$index.':AD'.$index] = '0.00';
                $arr_format_work_order['R'.$index.':AD'.$index] = '0.00';
                $arr_format_invoice['U'.$index.':AD'.$index] = '0.00';
                
                $coefficient_2 = 1;
                if ($assignment->d_table_spares_currency === 'USD') {
                    $coefficient_2 = $currency[0]->usd;
                }
                elseif ($assignment->d_table_spares_currency === 'EUR') {
                    $coefficient_2 = $currency[0]->eur;
                }
                $sum_spares += round(((int)$assignment->spares_sum_row/$coefficient_2),2);
            }
        }

        $index++;
        $arr_format_for_payment['R'.$index.':AD'.$index] = '0.00';
        $arr_format_work_order['R'.$index.':AD'.$index] = '0.00';
        $arr_format_invoice['U'.$index.':AD'.$index] = '0.00';

        for ($i=0; $i < 3; $i++) { 
            $index += 2;
            $arr_format_for_payment['R'.$index.':AD'.$index] = '0.00';
            $arr_format_work_order['R'.$index.':AD'.$index] = '0.00';
            $arr_format_invoice['U'.$index.':AD'.$index] = '0.00';
        }
        
        $GLOBALS['doc_name'] = $doc_name;
        $GLOBALS['total_in_words'] = $this->sum_translate($sum_work + $sum_spares);
        $GLOBALS['currency'] = $currency;
        $GLOBALS['assignment_data'] = $assignment_data;
        $GLOBALS['car_data'] = $car_data;        

        switch ($doc_name) {
            case 'invoice_for_payment':
                $GLOBALS['format_data'] = $arr_format_for_payment;
                break;
            
            case 'work_order':
                $GLOBALS['format_data'] = $arr_format_work_order;
                break;
            
            case 'invoice':
                $GLOBALS['format_data'] = $arr_format_invoice;
                break;
            
            default:
                $GLOBALS['format_data'] = [];
                break;
        }

        /*echo '<pre>'.print_r($arr_format_invoice,true).'</pre>';
        die();*/

        // Накладная
        $array_month = ['Ianuarie','Februarie','Martie','Aprilie','Mai','Iunie','Iulie','August','Septembrie','Octombrie','Noiembrie','Decembrie'];
        $arr_date = explode('-', date("Y-m-d"));
        $string_date = $arr_date[2].' '.$array_month[(int)$arr_date[1]-1].' '.$arr_date[0];
                
        $GLOBALS['invoice'] = 'AAD9238437';
        $GLOBALS['string_date'] = $string_date;
        $GLOBALS['currently'] = '';
        $GLOBALS['legal_address'] = '';
        $GLOBALS['iban'] = '';
        $GLOBALS['bank'] = '';
        $GLOBALS['swift'] = '';
        $GLOBALS['fiscal_code'] = '';
        $GLOBALS['vat'] = '';
        $GLOBALS['act_number'] = '';
        $GLOBALS['document_date'] = date("Y.m.d");
        $GLOBALS['amount_without_vat'] = $sum_work + $sum_spares;
        $GLOBALS['amount_vat'] = ($sum_work + $sum_spares)/5;
        $GLOBALS['total_amount'] = $sum_work + $sum_spares + $GLOBALS['amount_vat'];
        
        if($GLOBALS['invoice'] !== "")
        {
            $this->barcode($GLOBALS['invoice']);
        }

        if ($doc_name !== 'tax_invoice') {
            
            Excel::create('New file', function($excel) {
                $excel->sheet('New sheet', function($sheet) {

                    $sheet->loadView('templates_for_excel.'.$GLOBALS['doc_name'], array('assignment_data' => $GLOBALS['assignment_data'], 'car_data' => $GLOBALS['car_data'], 'currency' => $GLOBALS['currency'], 'total_in_words' => $GLOBALS['total_in_words']))->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  8,
                        )
                    ))->setColumnFormat($GLOBALS['format_data']);
                });
            })->download('xls');
        
        }
        else{

            Excel::load('excel/example.xls', function($excel)
            {                               
                $excel->sheet('TDSheet', function($sheet) {
                    
                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('excel/logo-sto.png'));
                    $objDrawing->setCoordinates('A1');
                    $objDrawing->setWorksheet($sheet);

                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('excel/barcode_'.$GLOBALS['invoice'].'.png'));
                    $objDrawing->setCoordinates('R10');
                    $objDrawing->setWorksheet($sheet);                    
                                    
                })
                ->setCellValueByColumnAndRow(10, 12, $GLOBALS['invoice'])
                ->setCellValueByColumnAndRow(4, 16, $GLOBALS['string_date'])
                ->setCellValueByColumnAndRow(12, 16, $GLOBALS['string_date'])
                ->setCellValueByColumnAndRow(4, 21, ($GLOBALS['currently'].", ".$GLOBALS['legal_address'].", c/d ".$GLOBALS['iban'].", ".$GLOBALS['bank'].", ".$GLOBALS['swift']))
                ->setCellValueByColumnAndRow(41, 21, $GLOBALS['fiscal_code']." / ".$GLOBALS['vat'])
                ->setCellValueByColumnAndRow(24, 23, "Act nr. ".$GLOBALS['act_number']." din ".$GLOBALS['document_date'])
                ->setCellValueByColumnAndRow(0, 32, "Servicii de reparatie auto conform ACT")
                ->setCellValueByColumnAndRow(9, 32, "serv.")
                ->setCellValueByColumnAndRow(12, 32, "1,00")
                ->setCellValueByColumnAndRow(15, 32, $GLOBALS['amount_without_vat'])
                ->setCellValueByColumnAndRow(17, 32, $GLOBALS['amount_without_vat'])
                ->setCellValueByColumnAndRow(19, 32, "20%")
                ->setCellValueByColumnAndRow(21, 32, $GLOBALS['amount_vat'])
                ->setCellValueByColumnAndRow(23, 32, $GLOBALS['total_amount'])
                ->setCellValueByColumnAndRow(17, 71, $GLOBALS['amount_without_vat'])
                ->setCellValueByColumnAndRow(21, 71, $GLOBALS['amount_vat'])
                ->setCellValueByColumnAndRow(23, 71, $GLOBALS['total_amount'])
                ->setCellValueByColumnAndRow(17, 72, $GLOBALS['amount_without_vat'])
                ->setCellValueByColumnAndRow(21, 72, $GLOBALS['amount_vat'])
                ->setCellValueByColumnAndRow(23, 72, $GLOBALS['total_amount'])
                ;           

            }) -> download('xls');
        
        }
           
    }

    
    /* Поиск нарядов */
    public function search_assignment(Request $request){
        /* Получаем всю нужную информацию по нарядам */
        $assignments_data =
            DB::table('assignments')
                ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->join('new_sub_assignments', 'assignments.id', '=', 'new_sub_assignments.assignment_id')
                ->orderBy('order','ASC')
                ->select(
                        'assignments.*',
                        'employees.general_name AS employee_name',
                        'cars_in_service.general_name AS car_name',
                        'cars_in_service.vin_number AS vin_number',
                        'cars_in_service.release_year AS release_year',
                        'cars_in_service.reg_number AS reg_number',
                        'cars_in_service.car_color AS car_color',
                        'cars_in_service.engine_capacity AS engine_capacity',
                        'cars_in_service.fuel_type AS fuel_type',
                        'new_sub_assignments.d_table_workzone AS assignment_workzone'
                    )
                ->where('new_sub_assignments.work_row_index', '<>', null)
                ->get();
        $workzone_data = DB::table('workzones')->get();

        // Собираем зональные наряды в массив и фильтруем наряды согласно поиску 
        $temp_arr_obj = [];
        $temp_arr_workzone = [];
        $temp_id = $assignments_data[0]->order;
        $i = 0;
        
        for ( ;$i < count($assignments_data); $i++) { 
            if ($temp_id == $assignments_data[$i]->order) {
                $temp_arr_workzone[] = $assignments_data[$i]->assignment_workzone;
            }
            else{
                if ($request->reg_number) {
                    if ($assignments_data[$i-1]->reg_number === $request->reg_number) {
                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    }
                }
                elseif ($request->vin_number) {
                    if ($assignments_data[$i-1]->vin_number === $request->vin_number) {
                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    }
                }
                elseif ($request->num_assignment) {
                    if ($assignments_data[$i-1]->id === $request->num_assignment) {
                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    }
                }
                elseif ($request->car_brand) {
                    if ($request->car_model) {
                        if (stristr($assignments_data[$i-1]->car_name, $request->car_brand.' '.$request->car_model) !== FALSE) {
                            if ($request->release_year) {
                                if ($request->release_year == $assignments_data[$i-1]->release_year) {
                                    if ($request->engine_capacity) {
                                        if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                            if ($request->fuel_type) {
                                                if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                                }
                                            }
                                            else{
                                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                            }                                            
                                        }
                                    }
                                    else{
                                        if ($request->fuel_type) {
                                            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                            }
                                        }
                                        else{
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }
                                    }
                                }
                            }
                            else{
                                if ($request->engine_capacity) {
                                    if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                        if ($request->fuel_type) {
                                            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                            }
                                        }
                                        else{
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }                                            
                                    }
                                }
                                else{
                                    if ($request->fuel_type) {
                                        if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }
                                    }
                                    else{
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                            }
                        }
                    }                    
                    else{
                        if (stristr($assignments_data[$i-1]->car_name, $request->car_brand) !== FALSE) {
                            if ($request->release_year) {
                                if ($request->release_year == $assignments_data[$i-1]->release_year) {
                                    if ($request->engine_capacity) {
                                        if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                            if ($request->fuel_type) {
                                                if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                                }
                                            }
                                            else{
                                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                            }                                            
                                        }
                                    }
                                    else{
                                        if ($request->fuel_type) {
                                            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                            }
                                        }
                                        else{
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }
                                    }
                                }
                            }
                            else{
                                if ($request->engine_capacity) {
                                    if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                        if ($request->fuel_type) {
                                            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                            }
                                        }
                                        else{
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }                                            
                                    }
                                }
                                else{
                                    if ($request->fuel_type) {
                                        if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }
                                    }
                                    else{
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                            }
                        }
                    }                                        
                }
                elseif ($request->release_year) {
                    if ($request->release_year == $assignments_data[$i-1]->release_year) {
                        if ($request->engine_capacity) {
                            if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                if ($request->fuel_type) {
                                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                                else{
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }                                            
                            }
                        }
                        else{
                            if ($request->fuel_type) {
                                if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }
                            }
                            else{
                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                            }
                        }
                    }
                }
                elseif ($request->engine_capacity) {
                    if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                        if ($request->fuel_type) {
                            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                            }
                        }
                        else{
                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                        }                                            
                    }
                }
                elseif ($request->fuel_type) {
                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    }
                }
                else{
                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                }
                
                $temp_id = $assignments_data[$i]->order;
                $temp_arr_workzone = [];
                $temp_arr_workzone[] = $assignments_data[$i]->assignment_workzone;
            }                
        }

        if ($request->reg_number) {
            if ($assignments_data[$i-1]->reg_number === $request->reg_number) {
                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
            }
        }
        elseif ($request->vin_number) {
            if ($assignments_data[$i-1]->vin_number === $request->vin_number) {
                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
            }
        }
        elseif ($request->num_assignment) {
            if ($assignments_data[$i-1]->id === $request->num_assignment) {
                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
            }
        }
        elseif ($request->car_brand) {
            if ($request->car_model) {
                if (stristr($assignments_data[$i-1]->car_name, $request->car_brand.' '.$request->car_model) !== FALSE) {
                    if ($request->release_year) {
                        if ($request->release_year == $assignments_data[$i-1]->release_year) {
                            if ($request->engine_capacity) {
                                if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                    if ($request->fuel_type) {
                                        if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }
                                    }
                                    else{
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }                                            
                                }
                            }
                            else{
                                if ($request->fuel_type) {
                                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                                else{
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }
                            }
                        }
                    }
                    else{
                        if ($request->engine_capacity) {
                            if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                if ($request->fuel_type) {
                                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                                else{
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }                                            
                            }
                        }
                        else{
                            if ($request->fuel_type) {
                                if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }
                            }
                            else{
                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                            }
                        }
                    }
                }
            }                    
            else{
                if (stristr($assignments_data[$i-1]->car_name, $request->car_brand) !== FALSE) {
                    if ($request->release_year) {
                        if ($request->release_year == $assignments_data[$i-1]->release_year) {
                            if ($request->engine_capacity) {
                                if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                    if ($request->fuel_type) {
                                        if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                        }
                                    }
                                    else{
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }                                            
                                }
                            }
                            else{
                                if ($request->fuel_type) {
                                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                                else{
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }
                            }
                        }
                    }
                    else{
                        if ($request->engine_capacity) {
                            if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                                if ($request->fuel_type) {
                                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                    }
                                }
                                else{
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }                                            
                            }
                        }
                        else{
                            if ($request->fuel_type) {
                                if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                                }
                            }
                            else{
                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                            }
                        }
                    }
                }
            }                                        
        }
        elseif ($request->release_year) {
            if ($request->release_year == $assignments_data[$i-1]->release_year) {
                if ($request->engine_capacity) {
                    if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                        if ($request->fuel_type) {
                            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                            }
                        }
                        else{
                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                        }                                            
                    }
                }
                else{
                    if ($request->fuel_type) {
                        if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                        }
                    }
                    else{
                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    }
                }
            }
        }
        elseif ($request->engine_capacity) {
            if ($request->engine_capacity == $assignments_data[$i-1]->engine_capacity) {
                if ($request->fuel_type) {
                    if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                        $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                        $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    }
                }
                else{
                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                }                                            
            }
        }
        elseif ($request->fuel_type) {
            if ($assignments_data[$i-1]->fuel_type === $request->fuel_type) {
                $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
            }
        }
        else{
            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
        }

        $assignments_data = $temp_arr_obj;
      
        /*echo '<pre>'.print_r($assignments_data,true).'</pre>';
        die();*/
        return view('assignments_admin.assignments_admin_search', ['assignments' => $assignments_data, 'workzone_data' => $workzone_data]);
    }

    /* Добавления наряда: страница с формой */
    public function add_assignment_page($car_id){
        
        $client = Client::get_client_by_car_id($car_id);
        
        /* Собираем информация по сотрудникам, которых можно указать как ответственных */
        $employees = Employee::where('status', 'active')->get();

        /* Информация о машине */
        $car = Cars_in_service::find($car_id);
        
        return view(
            'admin.assignments.add_assignment_page',
            [
                'employees' => $employees,
                'owner' => $client,
                'car' => $car
            ]
        );
    }
    
    /* Добавления наряда: страница обработки POST данных*/
    public function add_assignment_page_post(Request $request){
        /* Получаем данные из запроса */
        $responsible_employee_id = $request->responsible_employee_id;
        $assignment_description = $request->assignment_description;
        $car_id = $request->car_id;

        // Получаем кол-во нарядов
        $total_assignments = Assignment::count();

        /* Информация о клиенте и его машине для телеграма */
        $client = Client::get_client_by_car_id($car_id);
        $car = Cars_in_service::find($car_id);

        /* Создаём новый наряд и сохраняем его*/
        $new_assignment = new Assignment();
        $new_assignment->responsible_employee_id = $responsible_employee_id;
        $new_assignment->description = $assignment_description;
        $new_assignment->car_id = $car_id;
        $new_assignment->date_of_creation = date('Y-m-d');
        $new_assignment->status = 'active';
        $new_assignment->order = $total_assignments + 1;
        /* TEST confirmed */
        $new_assignment->confirmed = 'unconfirmed';
        /* end TEST confirmed */
        $new_assignment->save();

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_assignment_notification == 1){

            /* Оповещения для телеграма */
            $text = "У вас новый наряд!\n"
            . "<b>Клиент: </b>\n"
            . "$client->fio\n"
            . "<b>Авто: </b>\n"
            . "$car->general_name\n"
            . "<b>Дата: </b>\n"
            . "$new_assignment->date_of_creation\n"
            . "<b>Описание: </b>\n"
            .  $assignment_description;

            Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
            ]);

        } else {

        }
        

        /* Возвращаемся на страницу нарядов по авто */
        return redirect('admin/cars_in_service/view/'.$car_id);
    }

    /* Просмотр наряда : страница */
    public function view_assignment($assignment_id){
        /* Получаем информацию по наряду */
        $assignment = Assignment::find($assignment_id);

        /* Получаем дополнительную информацию по нарядам */
        /* Имя клиента */
        $assignment->client_name = $assignment->get_client_name();
        /* Id клиента */
        $assignment->client_id = $assignment->get_client_id();
        $client = Client::find($assignment->client_id);
        $assignment->client_organization = $client->organization;
        $assignment->client_fio = $client->fio;
        $assignment->client_surname = $client->surname;
        $assignment->client_phone = $client->phone;
        $assignment->client_balance = $client->balance;
        $assignment->client_discount = $client->discount;
        /* Авто */
        $assignment->car_name = $assignment->get_car_name();
        $assignment->car_brand = $assignment->get_car_brand();
        $assignment->car_model = $assignment->get_car_model();
        $assignment->car_mileage_km = $assignment->get_car_mileage_km();
        $assignment->car_fuel_type = $assignment->get_car_fuel_type();
        $assignment->car_engine_capacity = $assignment->get_car_engine_capacity();
        $assignment->car_vin_number = $assignment->get_car_vin_number();
        $assignment->car_color = $assignment->get_car_color();
        $assignment->car_year = $assignment->get_car_year();
        $assignment->car_reg_number = $assignment->get_car_reg_number();

                /* Доход/расход/работы */
                /* Получаем доходную часть */
                // $assignment_income = Assignments_income::where('assignment_id', $assignment_id)->get();
                $assignment_income = Assignments_income::where('assignment_id', $assignment_id)
                ->join('assignments', 'assignments_income.assignment_id', '=', 'assignments.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->join('clients', 'cars_in_service.owner_client_id', '=', 'clients.id')
                ->orderBy('order','ASC')
                ->select(
                        'assignments_income.*',
                        'assignments.id AS assignment_id',
                        'assignments.car_id AS assignment_car_id',
                        'cars_in_service.general_name AS assignment_car_name',
                        'cars_in_service.release_year AS assignment_release_year',
                        'cars_in_service.reg_number AS assignment_reg_number',
                        'clients.general_name AS assignment_client_name'
                    )
                ->get();

                /* Получаем зональную доходную часть */
                $zonal_assignment_income = Sub_assignment::where('assignment_id', $assignment_id)
                ->join('zonal_assignments_income', 'sub_assignments.id', '=', 'zonal_assignments_income.sub_assignment_id')
                ->join('assignments', 'sub_assignments.assignment_id', '=', 'assignments.id')
                ->orderBy('order','ASC')
                ->select(
                    'sub_assignments.*',
                    'sub_assignments.id AS sub_assignment_id',
                    'zonal_assignments_income.zonal_amount AS sub_as_amount',
                    'assignments.id AS assignment_id'
                )
                ->get();

                //dd($zonal_assignment_income);

                /* Получаем расходную часть */
                $assignment_expense = Assignments_expense::where('assignment_id', $assignment_id)->get();
                /* Получаем выполненые работы */
                $assignment_work = Assignments_completed_works::where('assignment_id', $assignment_id)->get();

        /* Получаем список зональных нарядов */
        $sub_assignments = 
            DB::table('sub_assignments')
            ->where('assignment_id', $assignment_id)
            ->join('workzones', 'sub_assignments.workzone_id', '=', 'workzones.id')
            ->orderBy('order','ASC')
            ->select('sub_assignments.*', 'workzones.general_name')
            ->get();


        /* Собираем дополнительные данные по зональным нарядам */
        foreach($sub_assignments as $sub_assignment){
            /* Название рабочей зоны */
            $sub_assignment->workzone_name = Workzone::find($sub_assignment->workzone_id)->general_name;
            
            /* Имя ответственного работника */
            $sub_assignment->responsible_employee = Employee::find($sub_assignment->responsible_employee_id)->general_name;
        }

        /* Получаем список картинок по наряду */
        $images = [];
        foreach(Storage::files('public/'.$assignment_id) as $file){
             $images[] = $file;
        }

        /* Получаем список картинок по наряду */
        $accepted_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/accepted') as $file){
             $accepted_images[] = $file;
        }

        /* Получаем список картинок по наряду */
        $repair_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/repair') as $file){
             $repair_images[] = $file;
        }
        
        /* Получаем список картинок по наряду */
        $finished_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/finished') as $file){
             $finished_images[] = $file;
        }

        // .. Собираем информацию по зональным нарядам

        $sub_assignment_id = [];

        /* Получаем массив id зональных нарядов */
        $sub_assignment_ids = DB::table('sub_assignments')->where('assignment_id', $assignment_id)->pluck('id');
        foreach ($sub_assignment_ids as $value) {
            $sub_assignment_id[] = $value;
        }
        //echo '<pre>'.print_r($sub_assignment_id,true).'</pre>';
        /* Получаем доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::whereIn('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::whereIn('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }

        // Получаем рабочие посты
        $workzone_data = DB::table('workzones')->get();
        //Получаем список работников
        $employees = Employee::where([
          ['status', '=', 'active'],
          ['fixed_charge', '=', null]
        ])->get();
        // Получаем рабочие направления
        $work_directions = DB::table('work_directions')->get();
        
        // Получаем новые зональные наряды
        $new_sub_assignments_arr = [];
        
        foreach ($work_directions as $value) {
            $temp_arr = [];
            
            $new_sub_work_assignments = New_sub_assignment::where([
                    ['assignment_id','=' ,$assignment_id],
                    ['work_row_index','!=' ,null],
                    ['d_table_work_direction','=' ,$value->name],
                ])->get();
            $temp_arr[] = $new_sub_work_assignments;
            
            $new_sub_spares_assignments = New_sub_assignment::where([
                    ['assignment_id','=' ,$assignment_id],
                    ['spares_row_index','!=' ,null],
                    ['d_table_work_direction','=' ,$value->name],
                ])->get();
            $temp_arr[] = $new_sub_spares_assignments;
            
            $new_sub_assignments_arr[$value->id] = $temp_arr;
        }
        
        $currency_arr = ['MDL','USD','EUR'];

        /* Возвращаем представление */
        return view('admin.assignments.view_assignment_page',
            [
                'assignment' => $assignment,
                'sub_assignments' => $sub_assignments,
                'image_urls'=> $images,
                'accepted_image_urls'=> $accepted_images,
                'repair_image_urls'=> $repair_images,
                'finished_image_urls'=> $finished_images,
                'assignment_income' => $assignment_income,
                'zonal_assignment_income' => $zonal_assignment_income,
                'assignment_expense' => $assignment_expense,
                'zonal_assignment_income' => $zonal_assignment_income, 
                'zonal_assignment_expense' => $zonal_assignment_expense, 
                'assignment_work' => $assignment_work,
                'usd' => $usd,
                'eur' => $eur,
                'workzone_data' => $workzone_data,
                'employees' => $employees,
                'work_directions' => $work_directions,
                'new_sub_work_assignments' => $new_sub_work_assignments,
                'new_sub_spares_assignments' => $new_sub_spares_assignments,
                'currency_arr' => $currency_arr,
                'new_sub_assignments_arr' => $new_sub_assignments_arr,
            ]);
    }


    /* Обновления позации элемента таблицы */
    public function updateOrder(Request $request){

        $sub_assignments = Sub_assignment::all();

        foreach ($sub_assignments as $sub_assignment) {
            $sub_assignment->timestamps = false; // To disable update_at field updation
            $id = $sub_assignment->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $sub_assignment->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }

    /* Обновления позации элемента таблицы */
    public function updateMainOrder(Request $request){

        $assignments = Assignment::all();

        foreach ($assignments as $assignment) {
            $assignment->timestamps = false; // To disable update_at field updation
            $id = $assignment->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $assignment->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }


    /* Изменение названия наряда */
    public function change_assignment_name(Request $request){
        /* Меняем название наряда */
        $assignment_id = $request->assignment_id;
        $new_name = $request->new_name;

        $assignment = Assignment::find($assignment_id);
        $assignment->description = $new_name;
        $assignment->save();
        
        /* Возвращаемся на страницу наряда */
        return back();
    }

    /* Добавление зонального наряда : страница */
    public function add_sub_assignment_page($assignment_id){
        /* Получаем данные по основному наряду */
        $assignment = Assignment::find($assignment_id);

        /* Данные по рабочим зонам */
        $workzones = Workzone::all();

        /* Данные по сотрудникам, которых можно сделать ответственными */
        $employees = Employee::all();

        /* Возвращаем страницу добавления зонального наряда */
        return view('admin.assignments.add_sub_assignment_page', [
            'assignment' => $assignment, // "Родительский" наряд
            'workzones' => $workzones, //  Рабочие зоны
            'employees' => $employees // Сотрудники
        ]);
    }

    /* Добавление зонального наряда : POST */
    public function add_sub_assignment_post(Request $request){
        
        /* Получаем данные из запроса */
        $main_assignment_id = $request->assignment_id; // ID "родительского" наряда
        $sub_assignment_name = $request->name; // Название зонального наряда
        $sub_assignment_description = $request->description; // Описание зонального наряда
        $workzone_id = $request->workzone; // ID рабочей зоны
        $responsible_employee_id = $request->responsible_employee; // ID ответственного лица (employee.id)

        $start_time = $request->start_time;
        $end_time = $request->end_time;
        
        /* Создание нового зонального наряда */
        $sub_assignment = new Sub_assignment();
        $sub_assignment->assignment_id = $main_assignment_id;
        $sub_assignment->name = $sub_assignment_name;
        $sub_assignment->description = $sub_assignment_description;
        $sub_assignment->workzone_id = $workzone_id;
        $sub_assignment->responsible_employee_id = $responsible_employee_id;
        $sub_assignment->date_of_creation = date('Y-m-d');
        $sub_assignment->start_time = $start_time;
        $sub_assignment->end_time = $end_time;
        $sub_assignment->save();

        /* Возвращаемся на страницу */
        return redirect('/admin/assignments/view/'.$main_assignment_id);
    }
    
    /* Добавление нового зонального наряда : POST */
    public function add_new_sub_assignment_post(Request $request){

        /* Удаление нового зонального наряда : POST */
        if (!empty($request->valueRow) AND substr($request->valueRow, 2, 3) !== '000') {
            // Получаем данные из запроса
            $row_index = $request->valueRow;
            $assignment_id = $request->assignmentId;        

            DB::table('new_sub_assignments')
            ->where([
                ['assignment_id','=', $assignment_id],
                ['work_row_index','=', $row_index]
            ])
            ->delete();

            DB::table('new_sub_assignments')
            ->where([
                ['assignment_id','=', $assignment_id],
                ['spares_row_index','=', $row_index]
            ])
            ->delete();

            DB::table('assignments_expenses')
            ->where([
                ['assignment_id','=', $assignment_id],
                ['description','=', $row_index]
            ])
            ->delete();
            
            return $row_index;
        }
        
        /* Получаем данные из запроса */
        $sub_assignment_arr = $request->valueArr;       
        
        $temp_arr = [];
        for ($i=0; $i < count($sub_assignment_arr); $i++) {
        foreach ($sub_assignment_arr[$i] as $key => $value) {
                $temp_arr[$key] = $value;
            } 
        }

        $is_post_work_row = null;
        $is_post_spares_row = null;
        $row_index = null;

        if (isset($temp_arr['work_row_index'])) {
            $is_post_work_row = New_sub_assignment::where([
                ['assignment_id','=', $temp_arr['assignment_id']],
                ['work_row_index','=', $temp_arr['work_row_index']]
            ])->first();
            $row_index = $temp_arr['work_row_index'];
        }
        
        if (isset($temp_arr['spares_row_index'])) {
            $is_post_spares_row = New_sub_assignment::where([
                ['assignment_id','=', $temp_arr['assignment_id']],
                ['spares_row_index','=', $temp_arr['spares_row_index']]
            ])->first();
            $row_index = $temp_arr['spares_row_index'];
        }
        
        $assignment_expenses = Assignments_expense::where([
                ['assignment_id','=', $temp_arr['assignment_id']],
                ['description','=', $row_index]
        ])->first();

        /* Создание нового зонального наряда */
        if (!$is_post_work_row AND isset($temp_arr['work_row_index'])) {
            $sub_assignment = new New_sub_assignment();
            $sub_assignment_expenses = new Assignments_expense();
            $sub_assignment->assignment_id = $temp_arr['assignment_id'];
            $sub_assignment->d_table_work_direction = $temp_arr['d_table_work_direction'];
            $sub_assignment->number_sub_assignment = $temp_arr['number_sub_assignment'];
            $sub_assignment->work_row_index = $temp_arr['work_row_index'];
            $sub_assignment->d_table_workzone = $temp_arr['d_table_workzone'];
            $sub_assignment->d_table_time_start = $temp_arr['d_table_time_start'];
            $sub_assignment->d_table_time_finish = $temp_arr['d_table_time_finish'];
            $sub_assignment->d_table_responsible_officer = $temp_arr['d_table_responsible_officer'];
            $sub_assignment->d_table_list_completed_works = $temp_arr['d_table_list_completed_works'];
            $sub_assignment->d_table_quantity = $temp_arr['d_table_quantity'];
            $sub_assignment->d_table_price = $temp_arr['d_table_price'];
            $sub_assignment->d_table_currency = $temp_arr['d_table_currency'];
            $sub_assignment->work_sum_row = $temp_arr['d_table_quantity']*$temp_arr['d_table_price'];           
            $sub_assignment->work_is_locked = $temp_arr['work_is_locked'];            
            $sub_assignment->status = 'active';
            $sub_assignment_expenses->amount = ($sub_assignment->work_sum_row) ? $sub_assignment->work_sum_row : 0;
            $sub_assignment_expenses->assignment_id = $temp_arr['assignment_id'];
            $sub_assignment_expenses->basis = 'Зарплата сотруднику';
            $sub_assignment_expenses->description = $temp_arr['work_row_index'];
            $sub_assignment_expenses->currency = $temp_arr['d_table_currency'];
            $sub_assignment->save();
            $sub_assignment_expenses->save();
        }
        elseif (!$is_post_spares_row AND isset($temp_arr['spares_row_index'])) {
            $sub_assignment = new New_sub_assignment();
            $sub_assignment_expenses = new Assignments_expense();
            $sub_assignment->assignment_id = $temp_arr['assignment_id'];
            $sub_assignment->d_table_work_direction = $temp_arr['d_table_work_direction'];
            $sub_assignment->number_sub_assignment = $temp_arr['number_sub_assignment'];
            $sub_assignment->spares_row_index = $temp_arr['spares_row_index'];
            $sub_assignment->d_table_spares_detail = $temp_arr['d_table_spares_detail'];
            $sub_assignment->d_table_spares_vendor_code = $temp_arr['d_table_spares_vendor_code'];
            $sub_assignment->d_table_spares_unit_measurements = $temp_arr['d_table_spares_unit_measurements'];
            $sub_assignment->d_table_spares_quantity = $temp_arr['d_table_spares_quantity'];
            $sub_assignment->d_table_spares_price = $temp_arr['d_table_spares_price'];
            $sub_assignment->d_table_spares_currency = $temp_arr['d_table_spares_currency'];
            $sub_assignment->spares_sum_row = $temp_arr['d_table_spares_quantity']*$temp_arr['d_table_spares_price'];
            $sub_assignment->spares_is_locked = $temp_arr['spares_is_locked'];
            $sub_assignment->status = 'active';
            $sub_assignment_expenses->amount = ($sub_assignment->spares_sum_row) ? $sub_assignment->spares_sum_row : 0;
            $sub_assignment_expenses->assignment_id = $temp_arr['assignment_id'];
            $sub_assignment_expenses->basis = 'Расходы на запчасти';
            $sub_assignment_expenses->description = $temp_arr['spares_row_index'];
            $sub_assignment_expenses->currency = $temp_arr['d_table_spares_currency'];
            $sub_assignment->save();
            $sub_assignment_expenses->save();
        }
        /* Обновление нового зонального наряда */
        elseif($is_post_work_row){
            $is_post_work_row->work_row_index = $temp_arr['work_row_index'];
            $is_post_work_row->d_table_workzone = $temp_arr['d_table_workzone'];
            $is_post_work_row->d_table_time_start = $temp_arr['d_table_time_start'];
            $is_post_work_row->d_table_time_finish = $temp_arr['d_table_time_finish'];
            $is_post_work_row->d_table_responsible_officer = $temp_arr['d_table_responsible_officer'];
            $is_post_work_row->d_table_list_completed_works = $temp_arr['d_table_list_completed_works'];
            $is_post_work_row->d_table_quantity = $temp_arr['d_table_quantity'];
            $is_post_work_row->d_table_price = $temp_arr['d_table_price'];
            $is_post_work_row->d_table_currency = $temp_arr['d_table_currency'];
            $is_post_work_row->work_sum_row = $temp_arr['d_table_quantity']*$temp_arr['d_table_price'];            
            $is_post_work_row->work_is_locked = $temp_arr['work_is_locked'];
            if ($assignment_expenses) {
                $assignment_expenses->amount = ($is_post_work_row->work_sum_row) ? $is_post_work_row->work_sum_row : 0;
                $assignment_expenses->currency = $temp_arr['d_table_currency'];
                $assignment_expenses->save();
            }
            else{
                $sub_assignment_expenses = new Assignments_expense();
                $sub_assignment_expenses->amount = ($is_post_work_row->work_sum_row) ? $is_post_work_row->work_sum_row : 0;
                $sub_assignment_expenses->assignment_id = $temp_arr['assignment_id'];
                $sub_assignment_expenses->basis = 'Зарплата сотруднику';
                $sub_assignment_expenses->description = $temp_arr['work_row_index'];
                $sub_assignment_expenses->currency = $temp_arr['d_table_currency'];
                $sub_assignment_expenses->save();
            }                        
            $is_post_work_row->save();
        }
        elseif ($is_post_spares_row) {
            $is_post_spares_row->spares_row_index = $temp_arr['spares_row_index'];
            $is_post_spares_row->d_table_spares_detail = $temp_arr['d_table_spares_detail'];
            $is_post_spares_row->d_table_spares_vendor_code = $temp_arr['d_table_spares_vendor_code'];
            $is_post_spares_row->d_table_spares_unit_measurements = $temp_arr['d_table_spares_unit_measurements'];
            $is_post_spares_row->d_table_spares_quantity = $temp_arr['d_table_spares_quantity'];
            $is_post_spares_row->d_table_spares_price = $temp_arr['d_table_spares_price'];
            $is_post_spares_row->d_table_spares_currency = $temp_arr['d_table_spares_currency'];
            $is_post_spares_row->spares_sum_row = $temp_arr['d_table_spares_quantity']*$temp_arr['d_table_spares_price'];
            $is_post_spares_row->spares_is_locked = $temp_arr['spares_is_locked'];
            if ($assignment_expenses) {
                $assignment_expenses->amount = ($is_post_spares_row->spares_sum_row) ? $is_post_spares_row->spares_sum_row : 0;
                $assignment_expenses->currency = $temp_arr['d_table_spares_currency'];
                $assignment_expenses->save();
            }
            else{
                $sub_assignment_expenses = new Assignments_expense();
                $sub_assignment_expenses->amount = ($is_post_spares_row->spares_sum_row) ? $is_post_spares_row->spares_sum_row : 0;
                $sub_assignment_expenses->assignment_id = $temp_arr['assignment_id'];
                $sub_assignment_expenses->basis = 'Расходы на запчасти';
                $sub_assignment_expenses->description = $temp_arr['spares_row_index'];
                $sub_assignment_expenses->currency = $temp_arr['d_table_spares_currency'];
                $sub_assignment_expenses->save();
            }            
            $is_post_spares_row->save();
        }

        return $temp_arr;
    }

    /* Добавление фотографий к наряду : Страница */
    public function add_photo_to_assignment_page($assignment_id){
        /* Получаем текущий наряд, к которому будут добавляться фото */
        $assignment = Assignment::find($assignment_id);
        
        /* Отображаем представление */
        return view('admin.assignments.add_photo_to_assignment_page', ['assignment' => $assignment]);
    }

    /* Добавление фотографий к наряду : Обработка запроса */
    public function add_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->test->store('public/'.$assignment_id);
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Добавление фотографий принятой машины к наряду : Обработка запроса */
    public function add_accepted_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->accepted_photo->store('public/'.$assignment_id.'/accepted');
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Добавление фотографий процесса ремонта к наряду : Обработка запроса */
    public function add_repair_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->repair_photo->store('public/'.$assignment_id.'/repair');
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Добавление фотографий готовой машины к наряду : Обработка запроса */
    public function add_finished_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->finished_photo->store('public/'.$assignment_id.'/finished');
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Удаление фотографий : страница */
    public function delete_photos_page($assignment_id){
        /* Получить список фотографий по наряду */
        $images = [];
        foreach(Storage::files('public/'.$assignment_id) as $file){
             $images[] = $file;
        }

        /* Получаем список фото принятых машин по наряду */
        $accepted_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/accepted') as $file){
                $accepted_images[] = $file;
        }

        /* Получаем список фото процесса ремонта по наряду */
        $repair_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/repair') as $file){
                $repair_images[] = $file;
        }
        
        /* Получаем список фото выдачи готовых машин по наряду */
        $finished_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/finished') as $file){
                $finished_images[] = $file;
        }
        
        /* Вывести страницу */
        return view('admin.assignments.delete_photos_from_assignment_page', 
        [
            'images' => $images,
            'accepted_image_urls'=> $accepted_images,
            'repair_image_urls'=> $repair_images,
            'finished_image_urls'=> $finished_images,
            'assignment_id' => $assignment_id
            ]);
    }

    /* Удаление фотографий : POST */
    public function delete_photos_post(Request $request){
        /* Удалить фото */
        Storage::delete($request->path_to_file);
        
        /* Вернуться на страницу удаления фотографий */
        return redirect('admin/assignments/'.$request->assignment_id.'/delete_photos_page');
    }

    public function print_settings_page(){



        return view('admin.assignments.print_settings_page');
    }

    public function assignment_management($sub_assignment_id){

        $sub_assignment = Sub_assignment::find($sub_assignment_id); 
        $assignment = Assignment::find($sub_assignment->assignment_id); 
        
        // .. Собираем информацию по наряду
        
        /* Получаем доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::where('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::where('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем выполненые работы */
        $zonal_assignment_work = Zonal_assignments_completed_works::where('sub_assignment_id', $sub_assignment_id)->get();
    
        return view('admin.assignments.assignment_management',
        [
            'assignment' =>  $assignment,
            'sub_assignment' => $sub_assignment,
            'zonal_assignment_income' => $zonal_assignment_income, 
            'zonal_assignment_expense' => $zonal_assignment_expense, 
            'zonal_assignment_work' => $zonal_assignment_work
        ]);
    }

        /* Добавить заход денег : POST */
        public function add_income_post(Request $request){
            /* Создаём новое вхождение по заходу денег и вносим туда информацию */
            $new_income_entry = new Assignments_income();
            $new_income_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
            $new_income_entry->amount = $request->amount; /* Сумма захода */
            $new_income_entry->currency = $request->currency; /* Валюта захода */
            $new_income_entry->basis = $request->basis; /* Основание для захода денег */
            $new_income_entry->description = $request->description; /* Описание для захода */
            $new_income_entry->save();


            /* Проверка оповещенияй (включено ли) */
            $user_id = Auth::user()->id;
            $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();
    
            if($notification_check->tg_income_notification == 1){
    
                /* Оповещения для телеграма */
                $text = "У вас новый доход!\n"
                . "<b>ID Наряда: </b>\n"
                . "$new_income_entry->assignment_id\n"
                . "<b>Сумма Дохода: </b>\n"
                . "$new_income_entry->amount $new_income_entry->currency\n"
                . "<b>Основание: </b>\n"
                . "$new_income_entry->basis\n"
                . "<b>Описание: </b>\n"
                .  $new_income_entry->description;
    
                Telegram::sendMessage([
                'chat_id' => '-1001204206841.0',
                'parse_mode' => 'HTML',
                'text' => $text
                ]);
    
            } else {
    
            }
    
            
    
            /* Возвращаемся обратно на страницу наряда */
            return back();
        }
        /* Добавить расход денег : POST */
        public function add_expense_post(Request $request){
            /* Создаём новое вхождение по расходу денег и вносим туда информацию */
            $new_expense_entry = new Assignments_expense();
            $new_expense_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
            $new_expense_entry->amount = $request->amount; /* Сумма расхода */
            $new_expense_entry->currency = $request->currency; /* Валюта расхода */
            $new_expense_entry->basis = $request->basis; /* Основание для расхода денег */
            $new_expense_entry->description = $request->description; /* Описание для расхода */
            $new_expense_entry->save();

            /* Проверка оповещенияй (включено ли) */
            $user_id = Auth::user()->id;
            $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

            if($notification_check->tg_expense_notification == 1){

                /* Оповещения для телеграма */
                $text = "У вас новый расход!\n"
                . "<b>ID Наряда: </b>\n"
                . "$new_expense_entry->assignment_id\n"
                . "<b>Сумма Расхода: </b>\n"
                . "$new_expense_entry->amount $new_expense_entry->currency\n"
                . "<b>Основание: </b>\n"
                . "$new_expense_entry->basis\n"
                . "<b>Описание: </b>\n"
                .  $new_expense_entry->description;

                Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $text
                ]);

            } else {

            }
    
    
            /* Возвращаемся обратно на страницу наряда */
            return back();
        }
        /* Добавить выполненые работы : POST */
        public function add_works_post(Request $request){
            /* Создаём новое вхождение по выполненым работам и вносим туда информацию */
            $new_works_entry = new Assignments_completed_works();
            $new_works_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
            $new_works_entry->basis = $request->basis; 
            $new_works_entry->description = $request->description; 
            $new_works_entry->status = 'unconfirmed';
            $new_works_entry->save();
    
    
            /* Возвращаемся обратно на страницу наряда */
            return back();
        }
    /* Добавить зональный заход денег : POST */
    public function add_zonal_assignment_income(Request $request){
        /* Создаём новое вхождение по заходу денег и вносим туда информацию */
        $new_zonal_income_entry = new Zonal_assignments_income();
        $new_zonal_income_entry->sub_assignment_id = $request->sub_assignment_id; /* Идентификатор зонального наряда  */
        $new_zonal_income_entry->zonal_amount = $request->zonal_amount; /* Сумма захода */
        $new_zonal_income_entry->zonal_currency = $request->currency; /* Валюта захода */
        $new_zonal_income_entry->zonal_basis = $request->zonal_basis; /* Основание для захода денег */
        $new_zonal_income_entry->zonal_description = $request->zonal_description; /* Описание для захода */
        $new_zonal_income_entry->save();

         /* Проверка оповещенияй (включено ли) */
         $user_id = Auth::user()->id;
         $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();
 
         if($notification_check->tg_income_notification == 1){
 
             /* Оповещения для телеграма */
             $text = "У вас новый зональный доход!\n"
             . "<b>ID Наряда: </b>\n"
             . "$new_zonal_income_entry->sub_assignment_id\n"
             . "<b>Сумма Дохода: </b>\n"
             . "$new_zonal_income_entry->zonal_amount $new_zonal_income_entry->zonal_currency\n"
             . "<b>Основание: </b>\n"
             . "$new_zonal_income_entry->zonal_basis\n"
             . "<b>Описание: </b>\n"
             .  $new_zonal_income_entry->zonal_description;
 
             Telegram::sendMessage([
             'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
             'parse_mode' => 'HTML',
             'text' => $text
             ]);
 
         } else {
 
         }

        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить зональный расход денег : POST */
    public function add_zonal_assignment_expense(Request $request){
        /* Создаём новое вхождение по расходу денег и вносим туда информацию */
        $new_zonal_expense_entry = new Zonal_assignments_expense();
        $new_zonal_expense_entry->sub_assignment_id = $request->sub_assignment_id; /* Идентификатор зонального наряда */
        $new_zonal_expense_entry->zonal_amount = $request->zonal_amount; /* Сумма расхода */
        $new_zonal_expense_entry->zonal_currency = $request->currency; /* Валюта расхода */
        $new_zonal_expense_entry->zonal_basis = $request->zonal_basis; /* Основание для расхода денег */
        $new_zonal_expense_entry->zonal_description = $request->zonal_description; /* Описание для расхода */
        $new_zonal_expense_entry->save();

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_expense_notification == 1){

            /* Оповещения для телеграма */
            $text = "У вас новый зональный расход!\n"
            . "<b>ID Наряда: </b>\n"
            . "$new_zonal_expense_entry->sub_assignment_id\n"
            . "<b>Сумма Расхода: </b>\n"
            . "$new_zonal_expense_entry->zonal_amount $new_zonal_expense_entry->zonal_currency\n"
            . "<b>Основание: </b>\n"
            . "$new_zonal_expense_entry->zonal_basis\n"
            . "<b>Описание: </b>\n"
            .  $new_zonal_expense_entry->zonal_description;

            Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
            ]);

        } else {

        }


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить зональные выполненые работы : POST */
    public function add_zonal_assignment_works(Request $request){
        /* Создаём новое вхождение по выполненым работам и вносим туда информацию */
        $new_zonal_works_entry = new Zonal_assignments_completed_works();
        $new_zonal_works_entry->sub_assignment_id = $request->sub_assignment_id; /* Идентификатор наряда */
        $new_zonal_works_entry->zonal_basis = $request->zonal_basis; /* Основание для расхода денег */
        $new_zonal_works_entry->zonal_description = $request->zonal_description; /* Описание для расхода */
        $new_zonal_works_entry->save();


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }

    public function update_zonal_assignment_time(Request $request){

        $sub_assignment_id = $request->assignment_id;
        $new_start_time = $request->new_start_time;
        $new_end_time = $request->new_end_time;

        // $time = DB::table('sub_assignments')->where('id', $sub_assignment_id)

        DB::table('sub_assignments')
        ->where('id', '=', $sub_assignment_id)
        ->update([
            'start_time' => $new_start_time,
            'end_time' => $new_end_time
            ]);

        return back();
    }

    /* Отображения общей рентабельности */
    public function profitability_index(){

        // ** Получаем валюту **//

        // Конверт. 1 USD в MDL

        $exchange_rates = DB::table('exchange_rates')->first();

        //$usd_to_mdl = $rates->exchange('USD', 1, 'MDL');

        // Конверт. 1 EUR в MDL
        //$eur_to_mdl = $rates->exchange('EUR', 1, 'MDL');


        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }
        /* Получаем ежемесячные расходы */
        $profitability_months = Month_profitability::all();

        return view('assignments_admin.profitability_admin_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'profitability_months' => $profitability_months,
            'exchange_rates' => $exchange_rates
        ]);
    }

    /* Курс валют */
    public function add_exchange_rates(Request $request){
        /* Устанавливаем курс валют */        
        if (DB::table('exchange_rates')->select('usd')->get()->count() > 0) {
                DB::table('exchange_rates')
                ->update(['usd' => $request->usd_currency, 'eur' => $request->eur_currency]);
            }
            else{
                DB::table('exchange_rates')
                ->insert(['usd' => $request->usd_currency, 'eur' => $request->eur_currency]);
            }

            return back();
    }

    /* Отображение месячной рентабельности*/
    public function profitability_month_index(){
        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }

        $rental_price = 0;
        $electricity = 0;
        $water_supply = 0;
        $gas = 0;
        $cleaning = 0;
        $garbage_removal = 0;
        $other_expenses = 0;
        $date = date("Y-m-d");
        
        /* Получаем последнюю запись в таблице расходов */
        $month_profitability = Month_profitability::latest()->first();

        if ($month_profitability) {
            $rental_price = $month_profitability->rental_price;
            $electricity = $month_profitability->electricity;
            $water_supply = $month_profitability->water_supply;
            $gas = $month_profitability->gas;
            $cleaning = $month_profitability->cleaning;
            $garbage_removal = $month_profitability->garbage_removal;
            $other_expenses = $month_profitability->other_expenses;
            $date = $month_profitability->date;
        }        

        $start_date = $date;
        $end_date = $date;
        $date_arr[] = substr($date,0,-3);

        return view('assignments_admin.profitability_month_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'rental_price' => $rental_price,
            'electricity' => $electricity,
            'water_supply' => $water_supply,
            'gas' => $gas,
            'cleaning' => $cleaning,
            'garbage_removal' => $garbage_removal,
            'other_expenses' => $other_expenses,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_arr' => $date_arr,
        ]);
    }

    /* Отображение месячной рентабельности с заданной датой*/
    public function profitability_month_show($start_date, $end_date){
        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }

        /* Получаем ежемесячные расходы */
        $profitability_months = Month_profitability::all();

        /* Получаем записи в таблице расходов за определенный период*/

        $rental_price = 0;
        $electricity = 0;
        $water_supply = 0;
        $gas = 0;
        $cleaning = 0;
        $garbage_removal = 0;
        $other_expenses = 0;
        $date_arr = [];
        $start_date_new = substr($start_date,0,-3).'-01';
        $end_date_new = substr($end_date,0,-3).'-01';

        /* Если у нас даты включают один месяц*/
        if (substr($start_date,0,-3) === substr($end_date,0,-3)) {
            foreach($profitability_months as $value) {
                if (substr($end_date,0,-3) === substr($value->date,0,-3)) {
                    $month_profitability = Month_profitability::find($value->id);

                    $rental_price = $month_profitability->rental_price;
                    $electricity = $month_profitability->electricity;
                    $water_supply = $month_profitability->water_supply;
                    $gas = $month_profitability->gas;
                    $cleaning = $month_profitability->cleaning;
                    $garbage_removal = $month_profitability->garbage_removal;
                    $other_expenses = $month_profitability->other_expenses;
                    $date_arr[] = substr($month_profitability->date,0,-3);
                    break;
                }
            }
        }
        /* Если у нас даты включают несколько месяцев*/
        else{
            foreach($profitability_months as $value) {
                if ( strtotime(substr($value->date,0,-3).'-01') >= strtotime($start_date_new) AND strtotime(substr($value->date,0,-3).'-01') <= strtotime($end_date_new) ) {
                    
                    $month_profitability = Month_profitability::find($value->id);                                       
                    $rental_price += $month_profitability->rental_price;
                    $electricity += $month_profitability->electricity;
                    $water_supply += $month_profitability->water_supply;
                    $gas += $month_profitability->gas;
                    $cleaning += $month_profitability->cleaning;
                    $garbage_removal += $month_profitability->garbage_removal;
                    $other_expenses += $month_profitability->other_expenses;
                    $date_arr[] = substr($month_profitability->date,0,-3);
                }
            }
        }
        
        return view('assignments_admin.profitability_month_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'rental_price' => $rental_price,
            'electricity' => $electricity,
            'water_supply' => $water_supply,
            'gas' => $gas,
            'cleaning' => $cleaning,
            'garbage_removal' => $garbage_removal,
            'other_expenses' => $other_expenses,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_arr' => $date_arr,
        ]);
    }       

    /* Отображение месячной рентабельности со свежими данными*/
    public function profitability_month(Request $request){
        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }
        /* Получаем ежемесячные расходы */
        $profitability_months = Month_profitability::all();
        /* Проверяем наличие данных */
        $item = true;
        foreach($profitability_months as $value) {
            /* если есть данные в этом месяце, то обновляем */
            if (substr($request->date,0,-3) === substr($value->date,0,-3)) {
                $item = false;
                $month_profitability = Month_profitability::find($value->id);
                if (isset($request->rental_price)) {
                    $month_profitability->rental_price = $request->rental_price;
                }
                if (isset($request->electricity)) {
                    $month_profitability->electricity = $request->electricity;
                }
                if (isset($request->water_supply)) {
                    $month_profitability->water_supply = $request->water_supply;
                }
                if (isset($request->gas)) {
                    $month_profitability->gas = $request->gas;
                }
                if (isset($request->cleaning)) {
                    $month_profitability->cleaning = $request->cleaning;
                }
                if (isset($request->garbage_removal)) {
                    $month_profitability->garbage_removal = $request->garbage_removal;
                }
                if (isset($request->other_expenses)) {
                    $month_profitability->other_expenses = $request->other_expenses;
                }
                $month_profitability->save();
                
                $rental_price = $month_profitability->rental_price;
                $electricity = $month_profitability->electricity;
                $water_supply = $month_profitability->water_supply;                
                $gas = $month_profitability->gas;
                $cleaning = $month_profitability->cleaning;
                $garbage_removal = $month_profitability->garbage_removal;
                $other_expenses = $month_profitability->other_expenses;
                $date = $month_profitability->date;
                break;
            }
        }
        /* если нет, то добавляем */
        if ($item) {
            $new_month_profitability = new Month_profitability();
            $new_month_profitability->rental_price = $request->rental_price;
            $new_month_profitability->electricity = $request->electricity;
            $new_month_profitability->water_supply = $request->water_supply;
            $new_month_profitability->gas = $request->gas;
            $new_month_profitability->cleaning = $request->cleaning;
            $new_month_profitability->garbage_removal = $request->garbage_removal;
            $new_month_profitability->other_expenses = $request->other_expenses;
            $new_month_profitability->date = $request->date;
            $new_month_profitability->save();

            $rental_price = $request->rental_price;
            $electricity = $request->electricity;
            $water_supply = $request->water_supply;
            $gas = $request->gas;
            $cleaning = $request->cleaning;
            $garbage_removal = $request->garbage_removal;
            $other_expenses = $request->other_expenses;
            $date = $request->date;
        }

        $start_date = $date;
        $end_date = $date;
        $date_arr[] = substr($date,0,-3);

        return view('assignments_admin.profitability_month_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'rental_price' => $rental_price,
            'electricity' => $electricity,
            'water_supply' => $water_supply,
            'gas' => $gas,
            'cleaning' => $cleaning,
            'garbage_removal' => $garbage_removal,
            'other_expenses' => $other_expenses,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_arr' => $date_arr,
        ]);
    }    

}
