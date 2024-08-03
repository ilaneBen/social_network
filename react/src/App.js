import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import HomeReact from './components/HomeReact';
import LoginReact from './components/LoginReact';
import Logout from './components/Logout';
import RegistrationReact from './components/RegistrationReact';
import PostList from './components/PostList';
import PostForm from './components/PostForm';
import Profile from './components/Profile';
import Navbar from './components/Navbar';
import UserProfile from './components/UserProfile';
import UserSearch from './components/UserSearch';
import Feed from './components/Feed';
import axios from 'axios';
import '../src/styles/app.css';
import '../src/styles/style.css';

const App = () => {
    const [user, setUser] = useState(null);

    useEffect(() => {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            setUser(JSON.parse(storedUser));
        }
    }, []);

    const handleLogin = (userData) => {
        setUser(userData);
        localStorage.setItem('user', JSON.stringify(userData));
    };

    const handleLogout = () => {
        setUser(null);
        localStorage.removeItem('user');
    };

    const handleSearchUser = async (searchTerm) => {
        try {
            const response = await axios.get(`https://localhost:8000/api/users/search?term=${searchTerm}`);
            // Gérer les résultats de la recherche ici
            console.log(response.data);
        } catch (error) {
            console.error('Error searching users:', error);
        }
    };

    const handleUserSelected = (user) => {
      console.log('User selected:', user);
      // Autre logique à ajouter ici, comme la navigation vers le profil de l'utilisateur
  };

    return (
        <Router>
            <Navbar isLoggedIn={!!user} />
            <UserSearch onSearch={handleSearchUser} onUserSelected={handleUserSelected} />
            <Routes>
                <Route path="/login" element={<LoginReact onLogin={handleLogin} />} />
                <Route path="/logout" element={<Logout onLogout={handleLogout} />} />
                <Route path="/register" element={<RegistrationReact />} />
                <Route path="/profile" element={<Profile user={user} />} />
                <Route path="api/users/:id" element={<UserProfile loggedInUserId={user?.id}/>} />
                <Route path="/" element={<HomeReact />} />
                <Route path="/posts" element={<PostList />} />
                <Route path="/feed" element={<Feed />} />
                <Route
                    path="/new-post"
                    element={<PostForm userId={user?.id} onPostCreated={(newPost) => console.log(newPost)} />}
                />
            </Routes>
            
        </Router>
    );
};

export default App;
