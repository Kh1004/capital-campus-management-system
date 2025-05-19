<?php

namespace Database\Seeders;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            [
                'course_name' => 'Web Development Bootcamp',
                'course_type' => 'Full-time',
                'description' => 'Comprehensive web development course covering frontend and backend technologies.',
                'course_fee' => 49999.00,
                'course_duration' => '12 weeks',
                'start_date' => Carbon::now()->addDays(14),
                'end_date' => Carbon::now()->addWeeks(14),
                'email' => 'admissions@example.com',
            ],
            [
                'course_name' => 'Data Science Fundamentals',
                'course_type' => 'Part-time',
                'description' => 'Learn data analysis, visualization, and machine learning basics.',
                'course_fee' => 34999.00,
                'course_duration' => '16 weeks',
                'start_date' => Carbon::now()->addDays(21),
                'end_date' => Carbon::now()->addWeeks(20),
                'email' => 'admissions@example.com',
            ],
            [
                'course_name' => 'Mobile App Development',
                'course_type' => 'Online',
                'description' => 'Build cross-platform mobile applications using React Native.',
                'course_fee' => 29999.00,
                'course_duration' => '10 weeks',
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addWeeks(10),
                'email' => 'admissions@example.com',
            ],
            [
                'course_name' => 'Cybersecurity Essentials',
                'course_type' => 'Part-time',
                'description' => 'Learn the fundamentals of cybersecurity and ethical hacking.',
                'course_fee' => 44999.00,
                'course_duration' => '14 weeks',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addWeeks(18),
                'email' => 'admissions@example.com',
            ],
            [
                'course_name' => 'Cloud Computing with AWS',
                'course_type' => 'Online',
                'description' => 'Master cloud computing concepts using Amazon Web Services.',
                'course_fee' => 39999.00,
                'course_duration' => '8 weeks',
                'start_date' => Carbon::now()->addDays(21),
                'end_date' => Carbon::now()->addWeeks(10),
                'email' => 'admissions@example.com',
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['course_name' => $course['course_name']],
                $course
            );
        }
    }
}
