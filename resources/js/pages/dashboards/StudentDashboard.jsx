// src/components/StudentDashboard.js
import React, { useEffect, useState } from 'react';
import axios from 'axios';

const StudentDashboard = () => {
    const [student, setStudent] = useState(null);
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const studentId = 2; // Replace with dynamic 
    
    // Fetch student and course data from Laravel API
    useEffect(() => {
        const fetchData = async () => {
            try {
                const studentResponse = await axios.get(`http://localhost/api/students/${studentId}`);
                setStudent(studentResponse.data.data);

                // Fetch courses the student is enrolled in
                const coursesResponse = await axios.get(`http://localhost/api/courses?student_id=${studentId}`);
                setCourses(coursesResponse.data.data);

                setLoading(false);
            } catch (error) {
                setLoading(false);
                setError('Error fetching data');
            }
        };

        fetchData();
    }, [studentId]);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>{error}</div>;

    return (
        <div className="student-dashboard">
            <h2>Welcome, {student?.full_name}</h2>

            <div className="profile">
                <h3>Profile</h3>
                <div className="profile-details">
                    <img
                        src={student?.picture || '/default_pic.jpg'}
                        alt="Student Profile"
                        className="profile-picture"
                    />
                    <div className="profile-info">
                        <p><strong>Email:</strong> {student?.email}</p>
                        <p><strong>Phone:</strong> {student?.phone || 'Not provided'}</p>
                        <p><strong>Address:</strong> {student?.address || 'Not provided'}</p>
                        <p><strong>Date of Birth:</strong> {student?.date_of_birth || 'Not provided'}</p>
                        <p><strong>Enrollment Date:</strong> {student?.enrollment_date || 'Not provided'}</p>
                    </div>
                </div>
            </div>

            <div className="courses">
                <h3>Enrolled Courses</h3>
                {courses.length > 0 ? (
                    <ul>
                        {courses.map((course) => (
                            <li key={course.course_id} className="course-item">
                                <h4>{course.course_name}</h4>
                                <p><strong>Start Date:</strong> {course.start_date}</p>
                                <p><strong>End Date:</strong> {course.end_date}</p>
                                <p><strong>Status:</strong> {course.status}</p>
                            </li>
                        ))}
                    </ul>
                ) : (
                    <p>No courses found for this student.</p>
                )}
            </div>
        </div>
    );
};

export default StudentDashboard;
