<div class="px-4 py-8 mx-auto bg-gray-50 sm:px-6 lg:px-8 max-w-7xl">

    <div class="max-w-5xl mx-auto">

        <x-course.show-header :course="$course" :isInstructor="$isInstructor" />
        <x-course.show-detail-card :course="$course" />
        <x-course.show-requirements-card :course="$course" />
        <x-course.show-syllabus-card :course="$course"  />
        <x-course.show-enrollment-card :course="$course" :enrollment_status="$enrollment_status" :continueLearningLesson="$continueLearningLesson" :isNewEnrollment="$isNewEnrollment"  />

        @if ($enrollment_status)
            <x-course.show-review :course="$course" />
        @endif

    </div> 
</div> 
