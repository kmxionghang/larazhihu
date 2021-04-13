<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_vote_up()
    {
        $this->withExceptionHandling()
            ->post('/answers/1/up-votes')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_vote_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post("/answers/{$answer->id}/up-votes")
            ->assertStatus(201);

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function an_authenticated_user_can_cancel_vote_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post("/answers/{$answer->id}/up-votes");

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());

        $this->delete("/answers/{$answer->id}/up-votes");

        $this->assertCount(0, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_vote_up_only_once()
    {

        $this->signIn();

        $answer = create(Answer::class);
        try {
            $this->post("/answers/{$answer->id}/up-votes");
            $this->post("/answers/{$answer->id}/up-votes");
        } catch (\Exception $e) {
            $this->fail('Can not vote up twice.');
        }
        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());
    }
}