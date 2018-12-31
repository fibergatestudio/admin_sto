<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Shift;

class Finances_Admin_Controller extends Controller
{
    /* Главная страница с операциями, ожидающими подтверждения */
    public function finances_index(){
        // На этой странице будет подтверждение начислений балансов со смен
        
        // Получить все смены, которые закрыты, но по которым ещё не решён вопрос с оплатой
        $pending_shifts = Shift::where([['status', 'closed'], ['payment_status', 'none']])->get();

        // Выводим
        return view('admin.finances.finances_index', ['pending_shifts' => $pending_shifts]);
    }
}
