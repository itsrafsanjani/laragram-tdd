<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->post('/api/posts', [
            'caption' => 'Hello World',
            'location' => 'Dhaka - 1216, Bangladesh'
        ]);

        $post = Post::first();

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'caption' => 'Hello World',
                    'location' => 'Dhaka - 1216, Bangladesh',
                ],
                'links' => [
                    'self' => route('posts.show', $post),
                ]
            ]);
    }
}
