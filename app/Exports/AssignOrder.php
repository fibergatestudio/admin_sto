<?php

namespace App\Exports;

use App\Car_wash_complete_work;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Support\Facades\DB;

class AssignOrder implements FromView
{
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     return Car_wash_complete_work::all();
    // }

    public $id;
    
    public function __construct($id){
        $this->id = $id; //  $this->id
    }

    public function view(): View
    {

        $car_wash_clients = DB::table('car_wash_clients')->where('id', $this->id)->get();

        return view('admin.wash.wash_assignments.download.export_client', [
            'invoices' => Car_wash_complete_work::all(),
            'car_wash_clients' => $car_wash_clients
        ]);
    }
}
