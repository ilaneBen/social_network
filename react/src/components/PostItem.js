import React, { useState, useEffect } from 'react';
import axios from 'axios';

const PostItem = ({ post }) => {
    const [authorName, setAuthorName] = useState('');
    const [authorProfilePicture, setAuthorProfilePicture] = useState('');
    const createdAtDate = post.created_at ? new Date(post.created_at) : null;
    const formattedDate = createdAtDate ? createdAtDate.toLocaleString() : 'Date non valide';

    useEffect(() => {
        const fetchAuthorDetails = async () => {
            try {
                const response = await axios.get(`https://localhost:8000/api/users/${post.author.id}`);
                setAuthorName(response.data.username);
                setAuthorProfilePicture(`https://localhost:8000/${response.data.profilePicture}`);
            } catch (error) {
                console.error('Error fetching author details:', error);
            }
        };

        fetchAuthorDetails();
    }, [post.author.id]);

    return (
        <div className='post'>
        <div className='userpost'>
            {authorProfilePicture && <img className='imgpost' src={authorProfilePicture} alt={`${authorName}'s profile`} />}
            <p>{authorName}</p>
            </div>
            <p>Titre: {post.title}</p>
            <p>Content: {post.content}</p>
            <p>Le {formattedDate}</p>
        </div>
    );
};

export default PostItem;
