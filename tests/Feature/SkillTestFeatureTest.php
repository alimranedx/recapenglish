<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\SkillTestAttempt;
use App\Models\SkillTestConfig;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillTestFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_skill_test_landing_page_loads_successfully()
    {
        $response = $this->get(route('skill-test.index'));

        $response->assertStatus(200);
        $response->assertSee('50-Question Master Skill Test');
        $response->assertSee('All');
        $response->assertSee('Topics Included');
    }

    public function test_skill_test_start_generates_50_questions_and_includes_every_single_topic()
    {
        $response = $this->post(route('skill-test.start'));

        $this->assertDatabaseCount('skill_test_attempts', 1);

        $attempt = SkillTestAttempt::first();
        $this->assertNotNull($attempt);
        $this->assertEquals(50, count($attempt->question_ids));
        $this->assertEquals('in_progress', $attempt->status);

        // Verify that EVERY single topic with exercises is represented in this test
        $allTopicIdsWithExercises = Topic::where('is_published', true)
            ->has('exercises')
            ->pluck('id')
            ->sort()
            ->values()
            ->toArray();

        $topicsInTest = Exercise::whereIn('id', $attempt->question_ids)
            ->pluck('topic_id')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $this->assertEquals($allTopicIdsWithExercises, $topicsInTest, 'Every single topic must be represented in the test!');

        $response->assertRedirect(route('skill-test.take', $attempt->uuid));
    }

    public function test_subsequent_tests_generate_different_random_questions()
    {
        $this->post(route('skill-test.start'));
        $this->post(route('skill-test.start'));

        $attempts = SkillTestAttempt::orderBy('id', 'asc')->get();
        $this->assertCount(2, $attempts);

        $attempt1 = $attempts[0];
        $attempt2 = $attempts[1];

        // Verify both have 50 questions
        $this->assertCount(50, $attempt1->question_ids);
        $this->assertCount(50, $attempt2->question_ids);

        // Verify questions differ (randomization)
        $diff = array_diff($attempt1->question_ids, $attempt2->question_ids);
        $this->assertNotEmpty($diff, 'Subsequent tests should select different questions randomly');
    }

    public function test_student_can_take_test_and_submit_answers_and_view_score()
    {
        // 1. Start test
        $this->post(route('skill-test.start'));
        $attempt = SkillTestAttempt::latest()->first();

        // 2. Load take screen
        $takeResponse = $this->get(route('skill-test.take', $attempt->uuid));
        $takeResponse->assertStatus(200);
        $takeResponse->assertSee('Question Navigator');

        // 3. Prepare answers: answer first 20 correctly, answer next 10 incorrectly, leave 20 unanswered
        $exercises = $attempt->getOrderedExercises();
        $submittedAnswers = [];

        foreach ($exercises->values() as $idx => $ex) {
            $correctLetter = strtolower(trim($ex->correct_answer));
            if ($idx < 20) {
                // Correct
                $submittedAnswers[$ex->id] = $correctLetter;
            } elseif ($idx < 30) {
                // Incorrect
                $wrongLetter = ($correctLetter === 'a') ? 'b' : 'a';
                $submittedAnswers[$ex->id] = $wrongLetter;
            } else {
                // Unanswered
            }
        }

        // 4. Submit
        $submitResponse = $this->post(route('skill-test.submit', $attempt->uuid), [
            'answers' => $submittedAnswers,
            'duration_seconds' => 450,
        ]);

        $submitResponse->assertRedirect(route('skill-test.result', $attempt->uuid));

        // 5. Verify database state
        $attempt->refresh();
        $this->assertEquals('completed', $attempt->status);
        $this->assertEquals(20, $attempt->score);
        $this->assertEquals(20, $attempt->correct_count);
        $this->assertEquals(10, $attempt->incorrect_count);
        $this->assertEquals(20, $attempt->unanswered_count);
        $this->assertEquals(40.0, $attempt->percentage);
        $this->assertEquals(450, $attempt->duration_seconds);

        // Topic breakdown exists
        $this->assertNotEmpty($attempt->topic_breakdown);
        $this->assertCount(Topic::where('is_published', true)->has('exercises')->count(), $attempt->topic_breakdown);

        // 6. View Result page
        $resultResponse = $this->get(route('skill-test.result', $attempt->uuid));
        $resultResponse->assertStatus(200);
        $resultResponse->assertSee('20');
        $resultResponse->assertSee('Topic-by-Topic Mastery Matrix');

        // 7. Re-accessing take screen redirects to result
        $redirectResponse = $this->get(route('skill-test.take', $attempt->uuid));
        $redirectResponse->assertRedirect(route('skill-test.result', $attempt->uuid));
    }

    public function test_student_can_auto_save_progress_during_test()
    {
        $this->post(route('skill-test.start'));
        $attempt = SkillTestAttempt::latest()->first();

        $firstQuestionId = $attempt->question_ids[0];

        $saveResponse = $this->postJson(route('skill-test.save-progress', $attempt->uuid), [
            'answers' => [
                $firstQuestionId => 'b',
            ],
            'duration_seconds' => 65,
        ]);

        $saveResponse->assertStatus(200);
        $saveResponse->assertJson([
            'success' => true,
            'answered_count' => 1,
        ]);

        $attempt->refresh();
        $this->assertEquals(['b'], array_values($attempt->user_answers));
        $this->assertEquals(65, $attempt->duration_seconds);
    }

    public function test_admin_can_update_skill_test_settings_and_change_duration_and_fixed_topics(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Admin accesses settings page
        $editResponse = $this->actingAs($admin)->get(route('admin.skill-test-settings.edit'));
        $editResponse->assertStatus(200);
        $editResponse->assertSeeText('Skill Test Rules & Configuration');

        // Pick 3 specific topics to fix
        $topics = Topic::where('is_published', true)->take(3)->get();
        $fixedIds = $topics->pluck('id')->toArray();

        // 2. Admin updates settings: 30 minutes duration, 24 questions, fixed topic mode
        $updateResponse = $this->actingAs($admin)->put(route('admin.skill-test-settings.update'), [
            'title' => 'Grammar Sprint 30-Minute Test',
            'duration_minutes' => 30,
            'total_questions' => 24,
            'pass_percentage' => 70,
            'topic_mode' => 'fixed',
            'fixed_topic_ids' => $fixedIds,
            'instructions' => 'Follow all guidelines carefully.',
        ]);

        $updateResponse->assertSessionHas('success');

        $config = SkillTestConfig::getActive();
        $this->assertEquals(30, $config->duration_minutes);
        $this->assertEquals(24, $config->total_questions);
        $this->assertEquals(70, $config->pass_percentage);
        $this->assertEquals('fixed', $config->topic_mode);
        $this->assertEquals($fixedIds, $config->fixed_topic_ids);

        // 3. Start a test and verify it picks questions from only the fixed topics
        $startResponse = $this->post(route('skill-test.start'));
        $attempt = SkillTestAttempt::latest()->first();

        $this->assertEquals(24, count($attempt->question_ids));
        $this->assertEquals(24, $attempt->total_questions);

        $topicsInTest = Exercise::whereIn('id', $attempt->question_ids)
            ->pluck('topic_id')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        sort($fixedIds);
        $this->assertEquals($fixedIds, $topicsInTest, 'Only the selected fixed topics should be tested');

        // 4. Verify test screen shows 30 minutes countdown
        $takeResponse = $this->get(route('skill-test.take', $attempt->uuid));
        $takeResponse->assertStatus(200);
        $takeResponse->assertSee('Grammar Sprint 30-Minute Test');
        $takeResponse->assertSee('30:00');
    }
}
