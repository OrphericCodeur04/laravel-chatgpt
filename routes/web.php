<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Actions\ChatPrompt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/{id}', function($id) {
    $conversation = $id === 'new' ? null : Conversation::find($id);
    return view('conversation', [
        'conversation' => $conversation,
    ]);
})->name('conversation');

Route::post('chat/{id}', function(Request $request, ChatPrompt $prompt, $id) {
    if($id == 'new') {
        $conversation = Conversation::create();
    } else {
        $conversation = Conversation::find($id);
    }

    $conversation->messages()->create([
        'content' => $request->input('prompt'),
        'role' => 'user',
    ]);

    $messages = $conversation->messages->map(function (Message $message) {
        return [
            'content' => $message->content,
            'role' => 'user',
        ];
    })->toArray();

    $result = $prompt->handle($messages); // $request->input('prompt')

    //dd($result);

    $conversation->messages()->create([
        'content' => $result->choices[0]->message->content,
        'role' => 'assistant',
    ]);
    
    return redirect()->route('conversation', ['id' => $conversation->id]);

})->name('chat');

//Route::get('/', function () {
   // return view('welcome');
// });
