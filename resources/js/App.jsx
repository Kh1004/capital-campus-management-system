import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Login from './pages/Login';
import AdminDashboard from './pages/dashboards/AdminDashboard';
import InstructorDashboard from './pages/dashboards/InstructorDashboard';
import StaffDashboard from './pages/dashboards/StaffDashboard';
import StudentDashboard from './pages/dashboards/StudentDashboard';

function App() {
  return (
    <Routes>
      <Route path="/" element={<Login />} />
      <Route path="/dashboard/admin" element={<AdminDashboard />} />
      <Route path="/dashboard/instructor" element={<InstructorDashboard />} />
      <Route path="/dashboard/staff" element={<StaffDashboard />} />
      <Route path="/dashboard/student" element={<StudentDashboard />} />
    </Routes>
  );
}

export default App;
