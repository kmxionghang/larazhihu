<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
// 使用 `php artisan make:test QuestionTest --unit` 命令生成后, TestCase 默认使用的是下面的命名空间. 需要改成 `use Tests\TestCase;`
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_question_has_many_answers()
    {
        $question = factory(Question::class)->create();

        create(Answer::class, ['question_id' => $question->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $question->answers());
    }

    /** @test */
    public function questions_with_published_at_date_are_published()
    {
        $publishedQuestion1 = factory(Question::class)->state('published')->create();
        $publishedQuestion2 = factory(Question::class)->state('published')->create();
        $unpublishedQuestion = factory(Question::class)->state('unpublished')->create();

        $publishedQuestions = Question::published()->get();

        $this->assertTrue($publishedQuestions->contains($publishedQuestion1));
        $this->assertTrue($publishedQuestions->contains($publishedQuestion2));
        $this->assertFalse($publishedQuestions->contains($unpublishedQuestion));
    }

    /** @test */
    public function can_mark_an_answer_as_best()
    {
        $question = create(Question::class, ['best_answer_id' => null]);

        $answer = create(Answer::class, ['question_id' => $question->id]);

        $question->markAsBestAnswer($answer);

        $this->assertEquals($question->best_answer_id, $answer->id);
    }

    /** @test */
    public function it_can_detect_all_invited_users()
    {
        $question = create(Question::class, [
            'content' => '@Jane @Luke please help me!'
        ]);

        $this->assertEquals(['Jane','Luke'], $question->invitedUsers());
    }

    /** @test */
    public function questions_without_published_at_date_are_drafts()
    {
        $user = create(User::class);

        $draft1 = create(Question::class, ['user_id' => $user->id, 'published_at' => null]);
        $draft2 = create(Question::class, ['user_id' => $user->id, 'published_at' => null]);
        $publishedQuestion = create(Question::class, ['user_id' => $user->id, 'published_at' => Carbon::now()]);

        $drafts = Question::drafts($user->id)->get();

        $this->assertTrue($drafts->contains($draft1));
        $this->assertTrue($drafts->contains($draft2));
        $this->assertFalse($drafts->contains($publishedQuestion));
    }
}
