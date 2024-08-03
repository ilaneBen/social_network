import React, { useState } from 'react';
import api from '../api';
import axios from 'axios';

const RegistrationForm = () => {
    const [formData, setFormData] = useState({
        username: '',
        email: '',
        plainPassword: '',
        city: '',
        country: '',
        profilePicture: '', // Modifier pour gérer les fichiers
    });
    const [message, setMessage] = useState('');

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleFileChange = (e) => {
        setFormData({
            ...formData,
            profilePicture: e.target.files[0], // Stocker le fichier dans le state
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const data = new FormData();
        for (const [key, value] of Object.entries(formData)) {
            data.append(key, value); // Ajouter tous les champs au FormData
        }
    
        try {
            const response = await axios.post('https://localhost:8000/register/create', data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            setMessage('Registration successful! Please check your email to verify your account.');
        } catch (error) {
            if (error.response) {
                // Si une réponse d'erreur structurée est renvoyée par le serveur
                console.error('Registration error:', error.response.data);
            } else if (error.request) {
                // Si la requête a été faite mais pas de réponse reçue
                console.error('No response received:', error.request);
            } else {
                // Autres erreurs
                console.error('Error:', error.message);
            }
            setMessage('An error occurred during registration. Please try again.');
        }
    };
    

    return (
        <div className="container">
            {message && <p>{message}</p>}
            <form onSubmit={handleSubmit}>
                <div className="form-group">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        className="form-control"
                        value={formData.username}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        className="form-control"
                        value={formData.email}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="plainPassword"
                        className="form-control"
                        value={formData.plainPassword}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label>City</label>
                    <input
                        type="text"
                        name="city"
                        className="form-control"
                        value={formData.city}
                        onChange={handleChange}
                    />
                </div>
                <div className="form-group">
                    <label>Country</label>
                    <input
                        type="text"
                        name="country"
                        className="form-control"
                        value={formData.country}
                        onChange={handleChange}
                    />
                </div>
                <div className="form-group">
                    <label>Profile Picture</label>
                    <input
                        type="file"
                        name="profilePicture"
                        className="form-control"
                        onChange={handleFileChange}
                    />
                </div>
                <button type="submit" className="btn btn-primary">Register</button>
            </form>
        </div>
    );
};

export default RegistrationForm;
