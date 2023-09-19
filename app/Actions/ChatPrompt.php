<?php

namespace App\Actions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use OpenAI\Laravel\Facades\OpenAI;

class ChatPrompt
{
    use AsAction;

    public $commandSignature = 'inspire { prompt : The user prompt }';

    public function handle(array $messages) // string $prompt
    {
        return OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            
           /* [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ] */
            
        ]); // ->choices[0]->message->content
    }

    public function asCommand(Command $command)
    {
        $command->comment($this->handle($command->argument('prompt')));
    }
}
