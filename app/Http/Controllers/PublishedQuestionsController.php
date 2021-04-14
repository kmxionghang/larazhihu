<?php

namespace App\Http\Controllers;

use App\Events\PublishQuestion;
use App\Models\Question;
use App\Models\User;
use App\Notifications\YouWereInvited;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublishedQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question)
    {
        $this->authorize('update', $question);

        $question->publish();

//        $names = $question->invitedUsers();
//
//        foreach ($names as $name){
//            $user = User::whereName($name)->first();
//
//            if($user){
//                $user->notify(new YouWereInvited($question));
//            }
//        }

        event(new PublishQuestion($question));

        return redirect("/questions/{$question->id}")->with('flash', "发布成功！");
    }
}
