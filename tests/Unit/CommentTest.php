<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    use AddCommentContractTest;

    public function getCommentModel()
    {
        return create(Question::class);
    }

    /** @test */
    public function a_comment_has_morph_to_attribute()
    {
        $comment = create(Comment::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\MorphTo', $comment->commented());
    }

    /** @test */
    public function a_comment_belongs_to_an_owner()
    {
        $comment = create(Comment::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $comment->owner());
        $this->assertInstanceOf('App\Models\User', $comment->owner);
    }
}
