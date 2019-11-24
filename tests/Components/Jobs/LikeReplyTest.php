<?php

namespace Tests\Components\Jobs;

use App\User;
use Tests\TestCase;
use App\Models\Reply;
use App\Jobs\LikeReply;
use App\Exceptions\CannotLikeReplyTwice;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LikeReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function we_can_like_a_reply()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();

        $this->dispatch(new LikeReply($reply, $user));

        $this->assertTrue($reply->fresh()->isLikedBy($user));
    }

    /** @test */
    public function we_cannot_like_a_reply_twice()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();

        $this->dispatch(new LikeReply($reply, $user));

        $this->assertTrue($reply->fresh()->isLikedBy($user));

        $this->expectException(CannotLikeReplyMultipleTimes::class);

        $this->dispatch(new LikeReply($reply, $user));
    }
}
