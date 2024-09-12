<div>
    <h1 class="text-2xl font-bold mb-4">Profile: {{ $user->name }}</h1>
    
    <p>Email: {{ $user->email }}</p>
    <p>Role: {{ ucfirst($user->role) }}</p>

    @if($user->role == 'student')
        <p>Enrolled Courses:</p>
        <ul>
            @foreach($enrolledCourses as $course)
                <li>{{ $course->name }}</li>
            @endforeach
        </ul>
    @elseif($user->role == 'instructor')
        <p>Your Courses:</p>
        <ul>
            @foreach($instructorCourses as $course)
                <li>{{ $course->name }}</li>
            @endforeach
        </ul>
    @endif
</div>
