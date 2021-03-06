<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
 
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
}