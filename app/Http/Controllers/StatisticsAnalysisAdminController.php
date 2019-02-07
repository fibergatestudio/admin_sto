<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsAnalysisAdminController extends Controller
{
	private $data_table = [
		['name' => 'Иванов', 'wrongdoing' => 'Опоздание', 'date' => '2017-01-09'],
		['name' => 'Иванов', 'wrongdoing' => 'Опоздание', 'date' => '2017-04-29'],
		['name' => 'Иванов', 'wrongdoing' => 'Опоздание', 'date' => '2017-07-09'],
		['name' => 'Иванов', 'wrongdoing' => 'Штраф', 'date' => '2018-08-15'],
		['name' => 'Иванов', 'wrongdoing' => 'Штраф', 'date' => '2018-11-19'],
		['name' => 'Петров', 'wrongdoing' => 'Опоздание', 'date' => '2017-01-09'],
		['name' => 'Петров', 'wrongdoing' => 'Штраф', 'date' => '2017-06-22'],
		['name' => 'Петров', 'wrongdoing' => 'Опоздание', 'date' => '2018-01-09'],
		['name' => 'Петров', 'wrongdoing' => 'Опоздание', 'date' => '2018-10-19'],
		['name' => 'Сидоров', 'wrongdoing' => 'Штраф', 'date' => '2017-01-09'],
		['name' => 'Сидоров', 'wrongdoing' => 'Штраф', 'date' => '2017-05-14'],
		['name' => 'Сидоров', 'wrongdoing' => 'Штраф', 'date' => '2018-01-03'],
		['name' => 'Сидоров', 'wrongdoing' => 'Опоздание', 'date' => '2018-07-09'],
		['name' => 'Сидоров', 'wrongdoing' => 'Опоздание', 'date' => '2018-10-19'],
		['name' => 'Сидоров', 'wrongdoing' => 'Опоздание', 'date' => '2018-11-15'],
		['name' => 'Тимошенко', 'wrongdoing' => 'Штраф', 'date' => '2017-01-09'],
		['name' => 'Тимошенко', 'wrongdoing' => 'Штраф', 'date' => '2017-03-09'],
		['name' => 'Тимошенко', 'wrongdoing' => 'Штраф', 'date' => '2018-05-11'],
		['name' => 'Порошенко', 'wrongdoing' => 'Опоздание', 'date' => '2017-01-09'],
		['name' => 'Порошенко', 'wrongdoing' => 'Опоздание', 'date' => '2017-03-09'],
		['name' => 'Порошенко', 'wrongdoing' => 'Опоздание', 'date' => '2017-10-19'],
		['name' => 'Порошенко', 'wrongdoing' => 'Опоздание', 'date' => '2018-01-09'],
		['name' => 'Порошенко', 'wrongdoing' => 'Опоздание', 'date' => '2018-04-19'],
		['name' => 'Порошенко', 'wrongdoing' => 'Штраф', 'date' => '2018-04-19'],
		['name' => 'Порошенко', 'wrongdoing' => 'Штраф', 'date' => '2018-08-24'],
	];

	private $months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
	private $years = ['1991','1992','1993','1994','1995','1996','1997','1999','2000','2001','2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012','2013','2014','2015','2016','2017','2018'];
	
	/* Главная страница */
    public function index(){
    	//echo '<pre>'.print_r($this->data_table,true).'</pre>';
    	return view('admin.statistics_analysis.statistics_analysis', ['data_table_json' => json_encode($this->data_table,JSON_UNESCAPED_UNICODE), 'data_table' => $this->data_table, 'months' => $this->months, 'years' => $this->years]);
    }
}
