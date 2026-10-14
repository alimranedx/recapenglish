<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\Part;
use App\Models\Topic;
use App\Models\TopicRead;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users with defined roles
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@recapenglish.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@recapenglish.com'],
            [
                'name' => 'Academic Editor',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $student = User::updateOrCreate(
            ['email' => 'student@recapenglish.com'],
            [
                'name' => 'Tanvir Ahmed',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // 2. Load Processed Book Data
        $jsonPath = database_path('processed_book_data.json');
        if (File::exists($jsonPath)) {
            $partsData = json_decode(File::get($jsonPath), true);

            $firstTopic = null;

            foreach ($partsData as $pData) {
                $part = Part::create([
                    'title' => $pData['title'],
                    'description' => $pData['description'],
                    'order' => $pData['order'],
                    'is_active' => true,
                ]);

                foreach ($pData['chapters'] as $cData) {
                    $chapter = Chapter::create([
                        'part_id' => $part->id,
                        'title' => $cData['title'],
                        'slug' => $cData['slug'],
                        'summary' => $cData['summary'],
                        'order' => $cData['order'],
                        'is_published' => true,
                    ]);

                    foreach ($cData['topics'] as $tData) {
                        $topic = Topic::create([
                            'chapter_id' => $chapter->id,
                            'title' => $tData['title'],
                            'slug' => $tData['slug'],
                            'content' => $tData['content'],
                            'mnemonics' => $tData['mnemonics'],
                            'estimated_read_time' => $tData['estimated_read_time'],
                            'order' => $tData['order'],
                            'is_published' => true,
                        ]);

                        if (!$firstTopic) {
                            $firstTopic = $topic;
                        }

                        // Create Exercises
                        if (!empty($tData['exercises'])) {
                            foreach ($tData['exercises'] as $eIndex => $eData) {
                                Exercise::create([
                                    'topic_id' => $topic->id,
                                    'question' => $eData['question'] ?: 'Practice Question ' . ($eIndex + 1),
                                    'options' => !empty($eData['options']) ? $eData['options'] : ['a) Option A', 'b) Option B', 'c) Option C', 'd) Option D'],
                                    'correct_answer' => $eData['correct_answer'] ?: 'a',
                                    'explanation' => $eData['explanation'] ?: 'Review the rules and examples above for this concept.',
                                    'exam_reference' => $eData['exam_reference'] ?: null,
                                    'order' => $eIndex + 1,
                                ]);
                            }
                        }
                    }
                }
            }

            // Set initial last read topic for the sample student
            if ($firstTopic) {
                $student->update([
                    'last_topic_id' => $firstTopic->id,
                    'last_read_at' => now(),
                ]);

                TopicRead::create([
                    'user_id' => $student->id,
                    'topic_id' => $firstTopic->id,
                    'is_completed' => true,
                    'last_read_at' => now(),
                ]);

                Bookmark::create([
                    'user_id' => $student->id,
                    'topic_id' => $firstTopic->id,
                    'note' => 'Review the exception rules for personified nouns before exams.',
                ]);
            }
        }
    }
}
