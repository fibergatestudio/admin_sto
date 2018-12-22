<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client_request;

class ClientAppointmentsController extends Controller
{
    public function show_client_appointments(){
        $client_appointments = Client_request::all();

        return view('client_appointments.client_appointments_index',
            [
                'client_appointments' => $client_appointments
            ]);
    }

    public function approve_appointment(Request $request){
        $appointment = Client_request::find($request->appointment_id);
        $appointment->appointment_time = $request->time_for_appointment;
        $appointment->processed = true;
        $appointment->save();

        // !! Сделать редирект на страницу одобренных заявок
   
    }
}
