import React, { useState } from 'react';
import axios from 'axios';
import './App.css'; // We'll create this file for global styles

function App() {
    const [isLogin, setIsLogin] = useState(true);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');
    const [token, setToken] = useState(localStorage.getItem('authToken') || '');
    const [loggedInUser, setLoggedInUser] = useState(null);

    const API_URL = 'http://localhost:8000/api';

    // Function to check if token exists on mount and fetch user data if so
    React.useEffect(() => {
        const fetchUser = async () => {
            if (token) {
                try {
                    const response = await axios.get(`${API_URL}/user`, { // Assuming you'll have a /api/user endpoint
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    });
                    setLoggedInUser(response.data);
                } catch (error) {
                    console.error("Failed to fetch user data:", error);
                    // If token is invalid, clear it
                    setToken('');
                    localStorage.removeItem('authToken');
                }
            }
        };
        fetchUser();
    }, [token]); // Run once on mount, and if token changes

    const handleSubmit = async (e) => {
        e.preventDefault();
        setMessage('');

        try {
            let response;
            if (isLogin) {
                response = await axios.post(`${API_URL}/login`, {
                    email,
                    password,
                });
                setLoggedInUser(response.data.user);
                setToken(response.data.token);
                localStorage.setItem('authToken', response.data.token);
                setMessage('Login successful!');
            } else {
                response = await axios.post(`${API_URL}/register`, {
                    name,
                    email,
                    password,
                });
                setLoggedInUser(response.data.user);
                setToken(response.data.token);
                localStorage.setItem('authToken', response.data.token);
                setMessage('Registration successful! You are now logged in.');
            }
        } catch (error) {
            console.error('Authentication error:', error);
            setMessage(error.response?.data?.message || 'Authentication failed. Please try again.');
        }
    };

    const handleLogout = async () => {
        setMessage('');
        try {
            await axios.post(`${API_URL}/logout`, {}, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setToken('');
            setLoggedInUser(null);
            localStorage.removeItem('authToken');
            setMessage('Logged out successfully!');
        } catch (error) {
            console.error('Logout error:', error);
            setMessage(error.response?.data?.message || 'Logout failed. Please try again.');
        }
    };

    return (
        <div className="app-container">
            <h1>Task Manager</h1>
            {!token ? (
                <div className="auth-card">
                    <h2>{isLogin ? 'Login' : 'Register'}</h2>
                    <form onSubmit={handleSubmit}>
                        {!isLogin && (
                            <div className="form-group">
                                <label htmlFor="name">Name:</label>
                                <input
                                    type="text"
                                    id="name"
                                    value={name}
                                    onChange={(e) => setName(e.target.value)}
                                    required={!isLogin}
                                />
                            </div>
                        )}
                        <div className="form-group">
                            <label htmlFor="email">Email:</label>
                            <input
                                type="email"
                                id="email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="password">Password:</label>
                            <input
                                type="password"
                                id="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                            />
                        </div>
                        <button type="submit" className="btn btn-primary">
                            {isLogin ? 'Login' : 'Register'}
                        </button>
                    </form>
                    <p className="toggle-auth">
                        {isLogin ? "Don't have an account?" : "Already have an account?"}
                        <button
                            onClick={() => setIsLogin(!isLogin)}
                            className="btn-link"
                        >
                            {isLogin ? 'Register here.' : 'Login here.'}
                        </button>
                    </p>
                </div>
            ) : (
                <div className="welcome-card">
                    <h3>Welcome, {loggedInUser ? loggedInUser.name : 'User'}!</h3>
                    <p>You are logged in.</p>
                    <button onClick={handleLogout} className="btn btn-danger">
                        Logout
                    </button>
                </div>
            )}
            {message && (
                <p className={`message ${message.includes('failed') ? 'error' : 'success'}`}>
                    {message}
                </p>
            )}
        </div>
    );
}

export default App;
