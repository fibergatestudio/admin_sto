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

    public function setWebhook(){

        $response = Telegram::setWebhook(['url' => 'https://www.site-dev.com/776333854:AAFN5rVXzS4fT77nnkqOO9YMCJGFyZQmLbU/webhook']);
        dd($response);

    }

    public function unsetWebhook(){

        $response = Telegram::removeWebhook();
        dd($response);

    }

    public function getUpdates(){

        $response = Telegram::getUpdates();
        dd($response);


    }

    public function webhooktest(){

        $update = Telegram::commandsHandler(true);
        
        // Commands handler method returns an Update object.
        // So you can further process $update object 
        // to however you want.
        
        return 'ok';

    }
}