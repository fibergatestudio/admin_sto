<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery_passage;

class DeliveryPassagesController extends Controller
{
    public function index(){
    	$passages = Delivery_passage::paginate(10);
    	//echo '<pre>'.print_r($json_response,true).'</pre>';
        return view('delivery_passages',['passages' => $passages]);
    }

    public function processing_query(Request $request){

    	$response = '';

    	if (isset($request)) {
    		$response = $request->getContent();
    		$json = json_decode($request->getContent(),true);
    		
    		foreach ($json as $key => $value) {
    			if ($key == 'logs') {
    				for ($i=0; $i < count($value); $i++) {
    					$new_passage = new Delivery_passage(); 
    					$new_passage->log_id = (int)$value[$i]['logId'];
    					$new_passage->time = (int)$value[$i]['time'];
    					$new_passage->emp_id = $value[$i]['empId'];
    					$new_passage->internal_emp_id = $value[$i]['internalEmpId'];
    					$new_passage->direction = $value[$i]['direction'];
    					$new_passage->save();
    				}
    			}    			
    		}	                
    	}
    	
        return $response;
    }
}
