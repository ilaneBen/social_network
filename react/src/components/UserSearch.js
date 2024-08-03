import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Link } from 'react-router-dom';
import axios from 'axios';

const UserSearch = ({ loggedInUserId }) => {  // Déstructurer pour obtenir directement loggedInUserId
    const [searchTerm, setSearchTerm] = useState('');
    const [results, setResults] = useState([]);
    const navigate = useNavigate();

    const handleSearch = async (e) => {
        e.preventDefault();  // Empêcher le rechargement de la page lors de la soumission du formulaire
        try {
            const response = await axios.get('https://localhost:8000/api/users/search', {
                params: {
                    term: searchTerm,
                    userId: loggedInUserId
                }
            });
            setResults(response.data);
        } catch (error) {
            console.error('Error searching users:', error);
        }
    };

    const handleUserClick = (userId) => {
        navigate(`api/users/${userId}`);  // Naviguer vers la page de profil de l'utilisateur
    };

    return (
        <div className='formsearch'>
            <form className='usersearch' onSubmit={handleSearch}>
                <input
                    type="text"
                    value={searchTerm} 
                    onChange={(e) => setSearchTerm(e.target.value)} 
                    placeholder="Search users" 
                />
                <button type="submit">Search</button>
            </form>
            <ul className='ulsearch'>
                {results.map((user) => (
                    user.id !== loggedInUserId && (
                        <li key={user.id} onClick={() => handleUserClick(user.id)}>
                            <Link to={`/api/users/${user.id}`}>
                                <img 
                                    src={`https://localhost:8000/${user.profilePicture}`} 
                                    alt={`${user.username}'s profile`} 
                                    style={{ width: '50px', height: '50px', borderRadius: '50%' }}
                                />
                                {user.username}
                            </Link>
                        </li>
                    )
                ))}
            </ul>
        </div>
    );
};

export default UserSearch;
