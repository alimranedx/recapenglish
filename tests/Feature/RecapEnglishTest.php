<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Exercise;
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

    public function test_admin_can_manage_and_update_exercises(): void
    {
        $admin = User::where('role', 'admin')->first();
        $topic = Topic::first();

        // 1. Admin views questions index
        $response = $this->actingAs($admin)->get(route('admin.exercises.index'));
        $response->assertStatus(200);

        // 2. Admin adds a new practice question
        $createResponse = $this->actingAs($admin)->post(route('admin.exercises.store'), [
            'topic_id' => $topic->id,
            'question' => 'Which one is a Proper Noun?',
            'option_a' => 'Bangladesh',
            'option_b' => 'Country',
            'option_c' => 'River',
            'option_d' => 'City',
            'correct_answer' => 'a',
            'exam_reference' => 'BCS 40th',
            'explanation' => 'Bangladesh কোনো নির্দিষ্ট দেশের নিজস্ব নাম, তাই এটি Proper Noun।',
        ]);

        $createResponse->assertSessionHas('success');
        $exercise = Exercise::where('question', 'Which one is a Proper Noun?')->first();
        $this->assertNotNull($exercise);
        $this->assertEquals('a', $exercise->correct_answer);

        // 3. Admin edits and updates the question & answer
        $editResponse = $this->actingAs($admin)->get(route('admin.exercises.edit', $exercise->id));
        $editResponse->assertStatus(200);

        $updateResponse = $this->actingAs($admin)->put(route('admin.exercises.update', $exercise->id), [
            'topic_id' => $topic->id,
            'question' => 'Which one is a Proper Noun? (Updated)',
            'option_a' => 'Bangladesh',
            'option_b' => 'River',
            'option_c' => 'Water',
            'option_d' => 'Team',
            'correct_answer' => 'a',
            'exam_reference' => 'BCS 40th & 41st',
            'explanation' => 'Proper Noun সর্বদা Capital letter দিয়ে শুরু হয়।',
        ]);

        $updateResponse->assertSessionHas('success');
        $exercise->refresh();
        $this->assertEquals('Which one is a Proper Noun? (Updated)', $exercise->question);
        $this->assertEquals('BCS 40th & 41st', $exercise->exam_reference);

        // 4. Admin deletes the question
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.exercises.destroy', $exercise->id));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('exercises', ['id' => $exercise->id]);
    }

    public function test_admin_can_view_dashboard_with_topic_diagnostics_and_skill_test_reports(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Dashboard loads with analytics
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeText('Grammar Topic Difficulty & Diagnostic Analysis');
        $response->assertSeeText('MCQ Bank Pool');
        $response->assertSeeText('50-MCQ Skill Tests');

        // 2. Skill test submissions report page loads
        $reportResponse = $this->actingAs($admin)->get(route('admin.skill-tests.index'));
        $reportResponse->assertStatus(200);
        $reportResponse->assertSee('Skill Test Submissions');

        // 3. Students index loads with test stats column
        $studentsResponse = $this->actingAs($admin)->get(route('admin.students.index'));
        $studentsResponse->assertStatus(200);
        $studentsResponse->assertSee('Skill Tests');
    }

    public function test_admin_can_correct_exercise_answer_and_update_explanation_with_return_url(): void
    {
        $admin = User::where('role', 'admin')->first();
        $exercise = Exercise::first();

        $returnUrl = 'http://recapenglish.test/skill-test/test-review-uuid/result';

        // Admin corrects wrong answer from 'a' to 'c' and enhances explanation
        $response = $this->actingAs($admin)->put(route('admin.exercises.update', $exercise->id), [
            'topic_id' => $exercise->topic_id,
            'question' => $exercise->question,
            'option_a' => 'Option Alpha',
            'option_b' => 'Option Beta',
            'option_c' => 'Option Gamma Correct',
            'option_d' => 'Option Delta',
            'correct_answer' => 'c',
            'exam_reference' => '44th BCS Preliminary',
            'explanation' => 'সংশোধিত ব্যাখ্যা: নিয়ম অনুযায়ী সঠিক উত্তর Option (C)।',
            'return_url' => $returnUrl,
        ]);

        $response->assertRedirect($returnUrl);
        $response->assertSessionHas('success');

        $exercise->refresh();
        $this->assertEquals('c', $exercise->correct_answer);
        $this->assertEquals('44th BCS Preliminary', $exercise->exam_reference);
        $this->assertStringContainsString('সংশোধিত ব্যাখ্যা', $exercise->explanation);
    }
}
