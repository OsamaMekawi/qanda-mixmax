<?php

namespace App\Console\Commands;

use App\Question;
use Illuminate\Console\Command;

class QandARest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs command line to reset  based Q And A system.';

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
        if (!$this->confirm('are you sure you want to reset all answered question?')) {
            $this->info("Question reset has been canceled!");
        }else{
            $questions = Question::where('user_answer','!=',null)->get();

            foreach($questions as $question){
                $question->update(['user_answer'=>null]);

            }


            $this->info("Question has been reset successfully!");
        }


    }
}
