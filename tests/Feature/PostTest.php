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
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'caption' => 'Hello World',
                    'location' => 'Dhaka - 1216, Bangladesh',
                    'links' => [
                        'self' => route('posts.show', $post),
                    ]
                ]
            ]);
    }

    public function test_a_user_can_retrieve_posts()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $posts = Post::factory(2)->create(['user_id' => $user->id]);

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $posts->last()->id,
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email,
                        ],
                        'caption' => $posts->last()->caption,
                        'location' => $posts->last()->location,
                    ],
                    [
                        'id' => $posts->first()->id,
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email,
                        ],
                        'caption' => $posts->first()->caption,
                        'location' => $posts->first()->location,
                    ]
                ],
                'links' => [
                    'self' => route('posts.index')
                ]
            ]);

    }

    public function test_a_user_can_only_retrieve_their_posts()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $posts = Post::factory()->create();

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [],
                'links' => [
                    'self' => route('posts.index')
                ]
            ]);
    }
}
