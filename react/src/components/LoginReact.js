import React, { useState } from 'react';
import axios from 'axios';

const LoginReact = ({ error, last_username, csrfToken, onLogin }) => {
    const [message, setMessage] = useState('');

    const handleSubmit = async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const username = formData.get('_username');
        const password = formData.get('_password');

        console.log('Username:', username);
    console.log('Password:', password);

        try {
            const response = await axios.post('https://localhost:8000/login_check', {
               _username: username,
               _password: password,
            });
            onLogin(response.data);
            setMessage('Login successful!');
        } catch (error) {
            setMessage('Login failed. Please check your credentials and try again.');
            console.error('Error:', error);
        }
    };

    return (
        <form className="form" method="post" onSubmit={handleSubmit}>
            {message && (
                <div className="alert alert-info">
                    {message}
                </div>
            )}

            {error && (
                <div className="alert alert-danger">
                    {error.messageKey}
                </div>
            )}

            <h1 className="h3 mb-3 font-weight-normal">Please sign in</h1>
            <label htmlFor="username">Email</label>
            <input
                type="email"
                name="_username"
                id="username"
                className="form-control"
                autoComplete="email"
                required
                autoFocus
                defaultValue={last_username}
            />

            <label htmlFor="password">Password</label>
            <input
                type="password"
                name="_password"
                id="password"
                className="form-control"
                autoComplete="current-password"
                required
            />

            <input
                type="hidden"
                name="_csrf_token"
                value={csrfToken}
            />

            <button className="btn btn-lg btn-primary" type="submit">
                Sign in
            </button>
        </form>
    );
};

export default LoginReact;
