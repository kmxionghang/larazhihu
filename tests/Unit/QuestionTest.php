<?php

namespace Tests\Unit;

use App\Answer;
use App\Question;
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

        factory(Answer::class)->create(['question_id' => $question->id]);

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
}
