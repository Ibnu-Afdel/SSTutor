<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // First ensure we have some instructors
        $instructors = User::where('role', 'instructor')->get();
        
        if ($instructors->isEmpty()) {
            // Create some instructors if none exist
            $instructors = collect([
                User::create([
                    'name' => 'John Doe',
                    'username' => 'john_instructor',
                    'email' => 'john@instructor.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'status' => 'approved',
                ]),
                User::create([
                    'name' => 'Jane Smith',
                    'username' => 'jane_instructor',
                    'email' => 'jane@instructor.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'status' => 'approved',
                ]),
                User::create([
                    'name' => 'Mike Johnson',
                    'username' => 'mike_instructor',
                    'email' => 'mike@instructor.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'status' => 'approved',
                ])
            ]);
        }

        // Create categories
        $categories = [
            'Web Development',
            'Mobile Development',
            'Data Science',
            'Machine Learning',
            'DevOps',
            'Backend Development',
            'Frontend Development'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }

        // Define comprehensive course data with real structure
        $coursesData = [
            [
                'name' => 'Complete JavaScript Fundamentals',
                'description' => 'Master JavaScript from basics to advanced concepts. Learn variables, functions, objects, DOM manipulation, async programming, and modern ES6+ features.',
                'price' => 199.99,
                'original_price' => 299.99,
                'level' => 'beginner',
                'duration' => 480, // minutes
                'status' => 'published',
                'is_pro' => false,
                'discount' => true,
                'discount_type' => 'percent',
                'discount_value' => 33,
                'sections' => [
                    [
                        'title' => 'JavaScript Basics',
                        'description' => 'Introduction to JavaScript fundamentals',
                        'lessons' => [
                            [
                                'title' => 'What is JavaScript?',
                                'content' => 'Learn about JavaScript, its history, and why it\'s essential for web development.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/W6NZfCO5SIk',
                                'duration' => 15
                            ],
                            [
                                'title' => 'Variables and Data Types',
                                'content' => 'Understanding var, let, const, and different data types in JavaScript.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/9YWHvMWOMgU',
                                'duration' => 25
                            ],
                            [
                                'title' => 'Functions in JavaScript',
                                'content' => 'Learn how to create and use functions, arrow functions, and function expressions.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/N8ap4k_1QEQ',
                                'duration' => 30
                            ]
                        ]
                    ],
                    [
                        'title' => 'DOM Manipulation',
                        'description' => 'Learn to interact with HTML elements using JavaScript',
                        'lessons' => [
                            [
                                'title' => 'Understanding the DOM',
                                'content' => 'What is DOM and how JavaScript interacts with HTML elements.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/0ik6X4DJKCc',
                                'duration' => 20
                            ],
                            [
                                'title' => 'Event Handling',
                                'content' => 'Learn to handle clicks, form submissions, and other user interactions.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/XF1_MlZ5l6M',
                                'duration' => 25
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'React.js Complete Guide',
                'description' => 'Build modern web applications with React. Learn components, hooks, state management, and best practices for React development.',
                'price' => 299.99,
                'original_price' => 399.99,
                'level' => 'intermediate',
                'duration' => 720,
                'status' => 'published',
                'is_pro' => true,
                'discount' => true,
                'discount_type' => 'amount',
                'discount_value' => 100,
                'sections' => [
                    [
                        'title' => 'React Fundamentals',
                        'description' => 'Learn the basics of React components and JSX',
                        'lessons' => [
                            [
                                'title' => 'Introduction to React',
                                'content' => 'What is React and why use it for building user interfaces.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/Tn6-PIqc4UM',
                                'duration' => 20
                            ],
                            [
                                'title' => 'JSX and Components',
                                'content' => 'Understanding JSX syntax and creating your first React components.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/DLX62G4lc44',
                                'duration' => 35
                            ]
                        ]
                    ],
                    [
                        'title' => 'State and Props',
                        'description' => 'Managing component state and passing data with props',
                        'lessons' => [
                            [
                                'title' => 'Understanding Props',
                                'content' => 'How to pass data between React components using props.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/PHaECbrKgs0',
                                'duration' => 25
                            ],
                            [
                                'title' => 'State Management with useState',
                                'content' => 'Learn to manage component state using the useState hook.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/O6P86uwfdR0',
                                'duration' => 30
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Laravel 11 Mastery',
                'description' => 'Master Laravel framework for building robust web applications. Learn routing, controllers, models, blade templates, and more.',
                'price' => 249.99,
                'original_price' => 349.99,
                'level' => 'intermediate',
                'duration' => 600,
                'status' => 'published',
                'is_pro' => false,
                'sections' => [
                    [
                        'title' => 'Laravel Basics',
                        'description' => 'Getting started with Laravel framework',
                        'lessons' => [
                            [
                                'title' => 'Laravel Installation and Setup',
                                'content' => 'Learn how to install Laravel and set up your development environment.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/rIfdg_Ot-LI',
                                'duration' => 15
                            ],
                            [
                                'title' => 'MVC Architecture in Laravel',
                                'content' => 'Understanding the Model-View-Controller pattern in Laravel.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/pWG7ajC_OVo',
                                'duration' => 25
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Python for Data Science',
                'description' => 'Learn Python programming specifically for data analysis, visualization, and machine learning applications.',
                'price' => 179.99,
                'original_price' => 229.99,
                'level' => 'beginner',
                'duration' => 540,
                'status' => 'published',
                'is_pro' => true,
                'sections' => [
                    [
                        'title' => 'Python Fundamentals',
                        'description' => 'Learn Python basics for data science',
                        'lessons' => [
                            [
                                'title' => 'Python Basics for Data Science',
                                'content' => 'Introduction to Python syntax and basic concepts needed for data science.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/LHBE6Q9XlzI',
                                'duration' => 30
                            ],
                            [
                                'title' => 'Working with NumPy',
                                'content' => 'Learn NumPy for numerical computing and array operations.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/QUT1VHiLmmI',
                                'duration' => 40
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Node.js Backend Development',
                'description' => 'Build scalable backend applications with Node.js. Learn Express, MongoDB, authentication, and API development.',
                'price' => 219.99,
                'original_price' => 299.99,
                'level' => 'intermediate',
                'duration' => 480,
                'status' => 'published',
                'is_pro' => false,
                'sections' => [
                    [
                        'title' => 'Node.js Fundamentals',
                        'description' => 'Understanding Node.js and its ecosystem',
                        'lessons' => [
                            [
                                'title' => 'Introduction to Node.js',
                                'content' => 'What is Node.js and why use it for backend development.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/TlB_eWDSMt4',
                                'duration' => 20
                            ],
                            [
                                'title' => 'Express.js Framework',
                                'content' => 'Building web servers and APIs with Express.js framework.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/L72fhGm1tfE',
                                'duration' => 35
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Advanced CSS & Sass',
                'description' => 'Master advanced CSS techniques, Flexbox, Grid, animations, and Sass preprocessing for modern web design.',
                'price' => 149.99,
                'original_price' => 199.99,
                'level' => 'intermediate',
                'duration' => 360,
                'status' => 'published',
                'is_pro' => false,
                'sections' => [
                    [
                        'title' => 'Advanced CSS Techniques',
                        'description' => 'Modern CSS features and techniques',
                        'lessons' => [
                            [
                                'title' => 'CSS Grid Layout',
                                'content' => 'Master CSS Grid for creating complex responsive layouts.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/jV8B24rSN5o',
                                'duration' => 25
                            ],
                            [
                                'title' => 'CSS Flexbox Complete Guide',
                                'content' => 'Learn Flexbox for flexible and responsive layouts.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/JJSoEo8JSnc',
                                'duration' => 30
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'TypeScript Fundamentals',
                'description' => 'Learn TypeScript to write better JavaScript with static typing, interfaces, and advanced language features.',
                'price' => 159.99,
                'original_price' => 199.99,
                'level' => 'intermediate',
                'duration' => 300,
                'status' => 'published',
                'is_pro' => false,
                'sections' => [
                    [
                        'title' => 'TypeScript Basics',
                        'description' => 'Introduction to TypeScript and its benefits',
                        'lessons' => [
                            [
                                'title' => 'What is TypeScript?',
                                'content' => 'Introduction to TypeScript and its advantages over JavaScript.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/d56mG7DezGs',
                                'duration' => 15
                            ],
                            [
                                'title' => 'TypeScript Types and Interfaces',
                                'content' => 'Understanding TypeScript type system and creating interfaces.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/VR-2fFyeUyE',
                                'duration' => 25
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Docker & DevOps Essentials',
                'description' => 'Learn containerization with Docker, CI/CD pipelines, and essential DevOps practices for modern development.',
                'price' => 269.99,
                'original_price' => 349.99,
                'level' => 'advanced',
                'duration' => 420,
                'status' => 'published',
                'is_pro' => true,
                'sections' => [
                    [
                        'title' => 'Docker Fundamentals',
                        'description' => 'Learn containerization with Docker',
                        'lessons' => [
                            [
                                'title' => 'Introduction to Docker',
                                'content' => 'What is Docker and why containerization is important.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/fqMOX6JJhGo',
                                'duration' => 25
                            ],
                            [
                                'title' => 'Docker Containers and Images',
                                'content' => 'Understanding Docker containers, images, and basic commands.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/pTFZFxd4hOI',
                                'duration' => 30
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Vue.js 3 Complete Course',
                'description' => 'Build dynamic web applications with Vue.js 3. Learn composition API, components, routing, and state management.',
                'price' => 189.99,
                'original_price' => 249.99,
                'level' => 'intermediate',
                'duration' => 450,
                'status' => 'published',
                'is_pro' => false,
                'sections' => [
                    [
                        'title' => 'Vue.js Fundamentals',
                        'description' => 'Getting started with Vue.js framework',
                        'lessons' => [
                            [
                                'title' => 'Introduction to Vue.js',
                                'content' => 'What is Vue.js and its key features for building user interfaces.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/nhBVL41-_Cw',
                                'duration' => 20
                            ],
                            [
                                'title' => 'Vue Components',
                                'content' => 'Creating and using Vue components with the Composition API.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/bzlFvd0b65c',
                                'duration' => 30
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Machine Learning with Python',
                'description' => 'Learn machine learning concepts and implement algorithms using Python, scikit-learn, and TensorFlow.',
                'price' => 329.99,
                'original_price' => 449.99,
                'level' => 'advanced',
                'duration' => 600,
                'status' => 'published',
                'is_pro' => true,
                'sections' => [
                    [
                        'title' => 'Machine Learning Basics',
                        'description' => 'Introduction to machine learning concepts',
                        'lessons' => [
                            [
                                'title' => 'What is Machine Learning?',
                                'content' => 'Introduction to machine learning, types of learning, and applications.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/ukzFI9rgwfU',
                                'duration' => 25
                            ],
                            [
                                'title' => 'Linear Regression',
                                'content' => 'Understanding and implementing linear regression from scratch.',
                                'video_url' => 'https://www.youtube-nocookie.com/embed/7ArmBVF2dCs',
                                'duration' => 40
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Create courses with their sections and lessons
        foreach ($coursesData as $courseData) {
            $instructor = $instructors->random();
            
            $course = Course::create([
                'name' => $courseData['name'],
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'original_price' => $courseData['original_price'] ?? $courseData['price'],
                'level' => $courseData['level'],
                'duration' => $courseData['duration'],
                'status' => $courseData['status'],
                'is_pro' => $courseData['is_pro'],
                'discount' => $courseData['discount'] ?? false,
                'discount_type' => $courseData['discount_type'] ?? null,
                'discount_value' => $courseData['discount_value'] ?? null,
                'instructor_id' => $instructor->id,
                'requirements' => 'Basic computer skills and internet access',
                'syllabus' => 'Complete course syllabus covering all essential topics with hands-on projects.',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
            ]);

            // Create sections and lessons for each course
            foreach ($courseData['sections'] as $sectionIndex => $sectionData) {
                $section = Section::create([
                    'course_id' => $course->id,
                    'title' => $sectionData['title'],
                    'description' => $sectionData['description'],
                    'order' => $sectionIndex + 1,
                ]);

                foreach ($sectionData['lessons'] as $lessonIndex => $lessonData) {
                    Lesson::create([
                        'section_id' => $section->id,
                        'course_id' => $course->id,
                        'title' => $lessonData['title'],
                        'content' => $lessonData['content'],
                        'video_url' => $lessonData['video_url'],
                        'duration' => $lessonData['duration'],
                        'order' => $lessonIndex + 1,
                    ]);
                }
            }

            echo "Created course: {$course->name} with " . $course->sections()->count() . " sections\n";
        }

        echo "\nSeeded " . count($coursesData) . " tech courses with sections and lessons!\n";
    }
}

