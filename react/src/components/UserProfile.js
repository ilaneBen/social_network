import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';
import '../styles/style.css'

const UserProfile = ({ loggedInUserId }) => {
    const [user, setUser] = useState(null);
    const [isFollowing, setIsFollowing] = useState(false);
    const [followersCount, setFollowersCount] = useState(0);
    const [followingCount, setFollowingCount] = useState(0);
    const { id } = useParams(); // Récupère l'ID de l'URL

    useEffect(() => {
        const fetchUser = async () => {
            try {
                console.log('Fetching user data');
                const response = await axios.get(`https://localhost:8000/api/users/${id}`);
                console.log('User data fetched:', response.data);
                setUser(response.data);

                if (loggedInUserId) {
                    console.log('Fetching followers count');
                    const followsResponse = await axios.get(`https://localhost:8000/api/follows/${id}/followers`);
                    console.log('Followers count fetched:', followsResponse.data);
                    setFollowersCount(followsResponse.data.count);

                    console.log('Fetching following count');
                    const followingResponse = await axios.get(`https://localhost:8000/api/follows/${id}/following`);
                    console.log('Following count fetched:', followingResponse.data);
                    setFollowingCount(followingResponse.data.count);

                    console.log('Checking if user is following');
                    const isFollowingResponse = await axios.get(`https://localhost:8000/api/follows/${loggedInUserId}/${id}`);
                    console.log('Following status fetched:', isFollowingResponse.data);
                    setIsFollowing(isFollowingResponse.data.isFollowing);
                }
            } catch (error) {
                console.error('Error fetching user:', error);
            }
        };

        fetchUser();
    }, [id, loggedInUserId]);

    const handleFollow = async () => {
        if (loggedInUserId == id) {
            alert("Vous ne pouvez pas vous suivre vous-même.");
            return;
        }
console.log(loggedInUserId, id)
        try {
            console.log('Sending follow request');
            await axios.post('https://localhost:8000/api/follow', {
                followerId: loggedInUserId,
                followingId: id
            });
            console.log('Follow request successful');
            setIsFollowing(true);
            setFollowersCount(followersCount + 1); // Met à jour le nombre de followers
            alert('Utilisateur suivi avec succès');
        } catch (error) {
            console.error('Erreur lors du suivi de l\'utilisateur:', error);
        }
    };

    if (!user) {
        return <div>Loading...</div>;
    }
    const imageUrl = `https://localhost:8000/${user.profilePicture}`;
    return (
        <div className='profile'>
            
            <img className='img-profil' src={imageUrl} alt="Profile" />
            <h1>{user.username}</h1>
            <p>{user.email}</p>
            <p>{user.city}, {user.country}</p>
            <p>Abonnés: {followersCount}</p>
            <p>Abonnement: {followingCount}</p>
            {loggedInUserId && !isFollowing && <button className='following' onClick={handleFollow}>Follow</button>}
            {loggedInUserId && isFollowing && <p className='following'>Vous suivez déja cette personne.</p>}
        </div>
    );
};

export default UserProfile;
