import React, { useState, useEffect } from 'react';
import axios from 'axios';
import PostList from './PostList';
import PostForm from './PostForm';
import '../styles/profile.css';

const Profile = ({ user }) => {
    const [posts, setPosts] = useState([]);
    const [followers, setFollowers] = useState([]);
    const [following, setFollowing] = useState([]);
    const [followersCount, setFollowersCount] = useState(0);
    const [followingCount, setFollowingCount] = useState(0);

    useEffect(() => {
        if (user && user.id) {
            fetchPosts();
            fetchFollowers();
            fetchFollowing();
        }
    }, [user]);

    const fetchPosts = async () => {
        try {
            const response = await axios.get('https://localhost:8000/api/posts');
            setPosts(response.data);
        } catch (error) {
            console.error('Error fetching posts:', error);
        }
    };

    const fetchFollowers = async () => {
        try {
            const response = await axios.get(`https://localhost:8000/api/follows/${user.id}/followers`);
            const followersData = response.data.data || response.data; // Ajustez selon la structure réelle de votre réponse
            setFollowers(followersData);
            setFollowersCount(followersData.count);
        } catch (error) {
            console.error('Error fetching followers:', error);
            setFollowers([]);
            setFollowersCount(0);
        }
    };

    const fetchFollowing = async () => {
        try {
            const response = await axios.get(`https://localhost:8000/api/follows/${user.id}/following`);
            const followingData = response.data.data || response.data; // Ajustez selon la structure réelle de votre réponse
            setFollowing(followingData);
            console.log(followingData)
            setFollowingCount(followingData.count);
        } catch (error) {
            console.error('Error fetching following:', error);
            setFollowing([]);
            setFollowingCount(0);
        }
    };

    const handlePostCreated = (newPost) => {
        setPosts([newPost, ...posts]);
    };

    if (!user) {
        return <div>Loading...</div>; // Affichage de chargement ou autre indication si l'utilisateur n'est pas encore défini
    }

    const { username, email, city, country, profilePicture } = user;
    const imageUrl = `https://localhost:8000/${profilePicture}`;
    const { id: userId } = user; // Assuming user.id is the correct property

    return (
        <div className="container profile-container">
            <div className="row">
                <div className="col-md-3 profile-sidebar">
                    <div className="profile-userpic">
                        <img src={imageUrl} className="img-responsive" alt="Profile" />
                         <div className="profile-usertitle-name">
                            {username}
                        </div>
                    </div>
                    <div className="profile-usertitle">
                       
                        {/* <div className="profile-usertitle-job">
                            {email}
                        </div> */}
                    </div>
                    <div className="profile-userinfo">
                        <h5>Informations supplémentaires</h5>
                        <ul className="list-group">
                            <li className="list-group-item">Ville: {city}</li>
                            <li className="list-group-item">Pays: {country}</li>
                        </ul>
                    </div>
                    <div className="profile-followers">
                        <h5>Abonnés <p>{followersCount}</p></h5>
                        <ul className="list-group">
                            {Array.isArray(followers) && followers.map((follower) => (
                                <li key={follower.id} className="list-group-item">
                                    {follower.username}
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div className="profile-following">
                        <h5>Abonnements <p>{followingCount}</p></h5>
                        <ul className="list-group">
                            {Array.isArray(following) && following.map((followed) => (
                                <li key={followed.id} className="list-group-item">
                                    {followed.username}
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
                <div className="col-md-9 profile-content">
                    <h3>Mes Publications</h3>
                    <PostForm userId={userId} onPostCreated={handlePostCreated} />
                    <PostList posts={posts} />
                </div>
            </div>
        </div>
    );
};

export default Profile;
