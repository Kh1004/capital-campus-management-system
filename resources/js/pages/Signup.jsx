import { useState } from 'react';
import axios from 'axios';
import { useNavigate, Link } from 'react-router-dom';

function SignUp() {
  const [fullName, setFullName] = useState('');
  const [regNo, setRegNo] = useState('');
  const [courseId, setCourseId] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [address, setAddress] = useState('');
  const [dateOfBirth, setDateOfBirth] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [picture, setPicture] = useState(null);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSignUp = async (e) => {
    e.preventDefault();

    if (password !== confirmPassword) {
      setError('Passwords do not match');
      return;
    }

    const formData = new FormData();
    formData.append('full_name', fullName);
    formData.append('reg_no', regNo);
    formData.append('course_id', courseId);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('address', address);
    formData.append('date_of_birth', dateOfBirth);
    formData.append('password', password);
    formData.append('picture', picture);  
    formData.append('enrollment_date', new Date().toISOString());
    formData.append('payment_status', 'pending'); 

    try {
      const res = await axios.post('/api/signup', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',  
        },
      });

      if (res.data.success) {
        navigate('/login');
      } else {
        setError('Sign up failed');
      }
    } catch (err) {
      setError('Error occurred during sign up');
    }
  };

  return (
    <div className="d-flex justify-content-center align-items-center min-vh-100" style={{ backgroundColor: '#e7ecf8' }}>
      <div className="card shadow p-4" style={{ width: '100%', maxWidth: '500px', borderRadius: '20px' }}>
        <div className="text-center mb-4">
          <img src="/images/image.png" alt="Logo" style={{ maxHeight: '100px' }} />
        </div>

        <form onSubmit={handleSignUp}>
          <div className="mb-3">
            <label className="form-label">Full Name</label>
            <input
              type="text"
              className="form-control"
              placeholder="John Doe"
              value={fullName}
              onChange={(e) => setFullName(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Registration Number</label>
            <input
              type="text"
              className="form-control"
              placeholder="123456"
              value={regNo}
              onChange={(e) => setRegNo(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Course ID</label>
            <input
              type="text"
              className="form-control"
              placeholder="Course ID"
              value={courseId}
              onChange={(e) => setCourseId(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Email Address</label>
            <input
              type="email"
              className="form-control"
              placeholder="username@gmail.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Phone Number</label>
            <input
              type="tel"
              className="form-control"
              placeholder="123-456-7890"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Address</label>
            <textarea
              className="form-control"
              placeholder="Enter your address"
              value={address}
              onChange={(e) => setAddress(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Date of Birth</label>
            <input
              type="date"
              className="form-control"
              value={dateOfBirth}
              onChange={(e) => setDateOfBirth(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Password</label>
            <input
              type="password"
              className="form-control"
              placeholder="••••••••"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Confirm Password</label>
            <input
              type="password"
              className="form-control"
              placeholder="••••••••"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label className="form-label">Profile Picture</label>
            <input
              type="file"
              className="form-control"
              onChange={(e) => setPicture(e.target.files[0])}
            />
          </div>

          {error && <div className="alert alert-danger">{error}</div>}

          <button type="submit" className="btn btn-primary w-100 rounded-pill fw-semibold">Sign Up</button>
          <div className="d-flex justify-content-between mt-3">
            <Link to="/login">Already have an account? Login</Link>
          </div>
        </form>
      </div>
    </div>
  );
}

export default SignUp;
