<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
 
class TelegramBotController extends Controller
{
    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        dd($activity);
    }
 
    public function sendMessage()
    {
        return view('message');
    }
 
    public function storeMessage(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'message' => 'required'
        // ]);


 
        // $text = "У вас новый контакт!\n"
        //     . "<b>Email адрес: </b>\n"
        //     . "$request->email\n"
        //     . "<b>Сообщение: </b>\n"
        //     . $request->message;

        $recipient = $request->id;
        $text = $request->message;
 
        Telegram::sendMessage([
            'chat_id' => $recipient,
            'parse_mode' => 'HTML',
            'text' => $text
        ]);
 
        return redirect()->back();
    }
 
    public function sendPhoto()
    {
        return view('photo');
    }
 
    public function storePhoto(Request $request)
    {
        $request->validate([
            'file' => 'file|mimes:jpeg,png,gif'
        ]);
 
        $photo = $request->file('file');
 
        Telegram::sendPhoto([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'photo' => InputFile::createFromContents(file_get_contents($photo->getRealPath()), str_random(10) . '.' . $photo->getClientOriginalExtension())
        ]);
 
        return redirect()->back();
    }

    public function getInfo(){

        $telegram = new Api('776333854:AAFN5rVXzS4fT77nnkqOO9YMCJGFyZQmLbU');

        $response = $telegram->getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();

        dd($response);


    }

    // public function setWebhook(){

    //     $response = Telegram::setWebhook(['url' => 'https://www.site-dev.com/776333854:AAFN5rVXzS4fT77nnkqOO9YMCJGFyZQmLbU/webhook']);
    //     dd($response);

    // }

    public function setWebhook()
    {
        $url = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/webhook';
        $response = Telegram::setWebhook(['url' => $url]);

        //$update = Telegram::commandsHandler(true);

        // dd($update);

        dd($response);
    
        //return $response == true ? redirect()->back() : dd($response);
    }

    public function unsetWebhook(){

        $response = Telegram::removeWebhook();
        dd($response);

    }

    public function getUpdates(){

        //$updates = Telegram::getWebhookUpdates();

        $response = Telegram::getWebhookInfo();
        //dd($)
        dd($response);


    }

    // public function handleRequest(Request $request)
    // {
    //     $this->chat_id = $request['message']['chat']['id'];
    //     $this->username = $request['message']['from']['username'];
    //     $this->text = $request['message']['text'];
 
    //     switch ($this->text) {
    //         case '/start':
    //         case '/menu':
    //             $this->showMenu();
    //             break;
    //         case '/info':
    //             $this->getTestMessage();
    //             break;
    //         default:
    //             $this->getTestMessage();
    //     }
    // }
 
    // public function showMenu($info = null)
    // {
    //     $message = '';
    //     if ($info) {
    //         $message .= $info . chr(10);
    //     }
    //     $message .= '/menu' . chr(10);
    //     $message .= '/getGlobal' . chr(10);
    //     $message .= '/getTicker' . chr(10);
    //     $message .= '/getCurrencyTicker' . chr(10);
 
    //     $this->sendMessage($message);
    // }
 
    // public function getTestMessage()
    // {
    //     $message = "Test Message";
 
    //     $this->sendMessage($message);
    // }

}