import React, { useEffect, useState } from 'react';
import axios from 'axios';

const InstructorDashboard = () => {
    const [instructor, setInstructor] = useState(null);
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const instructorId = 1; 

    useEffect(() => {
        const fetchData = async () => {
            try {
                const instructorRes = await axios.get(`http://localhost/api/instructors/${instructorId}`);
                setInstructor(instructorRes.data.data);

                const coursesRes = await axios.get(`http://localhost/api/courses?instructor_id=${instructorId}`);
                setCourses(coursesRes.data.data);

                setLoading(false);
            } catch (err) {
                console.error(err);
                setError('Error fetching instructor data');
                setLoading(false);
            }
        };

        fetchData();
    }, [instructorId]);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>{error}</div>;

    return (
        <div className="instructor-dashboard">
            <h2>Welcome, {instructor?.full_name}</h2>

            <div className="profile">
                <h3>Instructor Profile</h3>
                <div className="profile-details">
                    <img
                        src={instructor?.picture || '/default_instructor.jpg'}
                        alt="Instructor Profile"
                        className="profile-picture"
                    />
                    <div className="profile-info">
                        <p><strong>Email:</strong> {instructor?.email}</p>
                        <p><strong>Phone:</strong> {instructor?.phone || 'Not provided'}</p>
                        <p><strong>Department:</strong> {instructor?.department || 'Not specified'}</p>
                        <p><strong>Join Date:</strong> {instructor?.join_date || 'Not specified'}</p>
                        <p><strong>Bio:</strong> {instructor?.bio || 'No bio available'}</p>
                    </div>
                </div>
            </div>

            <div className="courses">
                <h3>Courses Teaching</h3>
                {courses.length > 0 ? (
                    <ul>
                        {courses.map(course => (
                            <li key={course.course_id} className="course-item">
                                <h4>{course.course_name}</h4>
                                <p><strong>Start Date:</strong> {course.start_date}</p>
                                <p><strong>End Date:</strong> {course.end_date}</p>
                                <p><strong>Students Enrolled:</strong> {course.enrollment_count || 'N/A'}</p>
                            </li>
                        ))}
                    </ul>
                ) : (
                    <p>No courses assigned yet.</p>
                )}
            </div>
        </div>
    );
};

export default InstructorDashboard;
