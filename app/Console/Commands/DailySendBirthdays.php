<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use Telegram\Bot\Laravel\Facades\Telegram;

class DailySendBirthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending the names of those who have a birthday today and tomorrow';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dateToday = date("m-d");
        $namesToday = ''; // имена тех у кого сегодня ДР
        $namesTomorrow = ''; // имена тех у кого завтра ДР
        foreach(Employee::getBirthdayToday() as $employee){                     
            $namesToday .= $employee->fio.', ';                                 
        }
        foreach(Employee::getBirthdayTomorrow() as $employee){                      
            $namesTomorrow .= $employee->fio.', ';                                  
        }
        $textToday = "Именинники сегодня:\n". $namesToday; // текст для телеграм
        $textTomorrow = "Именинники завтра:\n". $namesTomorrow; // текст для телеграм
        $text = $textToday."\n".$textTomorrow; // текст для телеграм
        
        // Посылка сообщений тем у кого ДР сегодня (сообщение о тех у кого ДР завтра)
        foreach(Employee::getBirthdayToday() as $employee){                     
            if(!empty($employee->telegram_id)){
                Telegram::sendMessage([
                    'chat_id' => $employee->telegram_id,
                    'parse_mode' => 'HTML',
                    'text' => $textTomorrow
                ]); 
            }else{  // если поле с данными telegram_id пустое, отсылаем в тестовый канал
                Telegram::sendMessage([
                    'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                    'parse_mode' => 'HTML',
                    'text' => $textTomorrow
                ]); 
            }                                   
        } //end foreach
        
        // Посылка сообщений всем работникам со статусом 'active', кроме именинников сегодня
        foreach(Employee::where('status', 'active')->get() as $employee){
            if($dateToday != $employee->birthday_m_d){
                if(!empty($employee->telegram_id)){
                    Telegram::sendMessage([
                        'chat_id' => $employee->telegram_id,
                        'parse_mode' => 'HTML',
                        'text' => $text
                    ]); 
                }else{  // если поле с данными telegram_id пустое, отсылаем в тестовый канал
                    Telegram::sendMessage([
                        'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                        'parse_mode' => 'HTML',
                        'text' => $text
                    ]); 
                }
            }                                               
        } // end foreach      
    }
}
