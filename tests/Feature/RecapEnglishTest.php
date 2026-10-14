<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Part;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecapEnglishTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_reader_home_loads_successfully_with_curriculum_parts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('RecapEnglish');
        $response->assertSee('Part I: The Building Blocks');
    }

    public function test_user_can_register_as_a_learner_only(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Learner',
            'email' => 'newlearner@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('reader.home'));

        $user = User::where('email', 'newlearner@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role);
    }

    public function test_reading_a_topic_auto_saves_user_last_topic_progress(): void
    {
        $student = User::where('role', 'user')->first();
        $topic = Topic::where('order', 1)->first();

        $response = $this->actingAs($student)->get(route('reader.topic', $topic->slug));

        $response->assertStatus(200);
        $response->assertSee($topic->title);

        $student->refresh();
        $this->assertEquals($topic->id, $student->last_topic_id);
        $this->assertNotNull($student->last_read_at);
    }

    public function test_user_can_toggle_topic_completion_and_bookmark(): void
    {
        $student = User::where('role', 'user')->first();
        $topic = Topic::where('order', 1)->first();

        // Toggle complete
        $response = $this->actingAs($student)->post(route('reader.toggle-complete', $topic->id));
        $response->assertSessionHas('success');

        // Toggle bookmark
        $response = $this->actingAs($student)->post(route('reader.toggle-bookmark', $topic->id), [
            'note' => 'Important exam mnemonic'
        ]);
        $response->assertSessionHas('success');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $student = User::where('role', 'user')->first();

        $response = $this->actingAs($student)->get('/admin');
        $response->assertStatus(403);

        $responseTopics = $this->actingAs($student)->get('/admin/topics');
        $responseTopics->assertStatus(403);
    }

    public function test_admin_can_access_topics_and_create_topics(): void
    {
        $admin = User::where('role', 'admin')->first();
        $chapter = Chapter::first();

        $response = $this->actingAs($admin)->get('/admin/topics');
        $response->assertStatus(200);

        // Admin can add a new topic
        $postResponse = $this->actingAs($admin)->post('/admin/topics', [
            'chapter_id' => $chapter->id,
            'title' => 'Newly Added Topic by Admin',
            'content' => 'This is content for newly added topic.',
            'mnemonics' => 'TEST-MNEMONIC',
            'estimated_read_time' => 5,
            'order' => 99,
            'is_published' => 1,
        ]);

        $postResponse->assertSessionHas('success');
        $this->assertDatabaseHas('topics', ['title' => 'Newly Added Topic by Admin']);
    }

    public function test_admin_cannot_access_super_admin_manage_admins_area(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/admins');
        $response->assertStatus(403);

        $responseCreate = $this->actingAs($admin)->get('/admin/admins/create');
        $responseCreate->assertStatus(403);
    }

    public function test_only_super_admin_can_access_and_create_new_admins(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($superAdmin)->get('/admin/admins');
        $response->assertStatus(200);

        // Super Admin provisions new Admin
        $createResponse = $this->actingAs($superAdmin)->post('/admin/admins', [
            'name' => 'Newly Provisioned Editor',
            'email' => 'editor2@recapenglish.com',
            'password' => 'Password123!',
            'role' => 'admin',
        ]);

        $createResponse->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'editor2@recapenglish.com',
            'role' => 'admin',
        ]);
    }
}
