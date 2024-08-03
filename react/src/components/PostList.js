// src/js/components/PostList.js

import React, { useEffect, useState } from 'react';
import axios from 'axios';
import PostItem from './PostItem';
import PostForm from './PostForm'; 

const PostList = (user) => {
    const [posts, setPosts] = useState([]);

    useEffect(() => {
        fetchPosts();
    }, []);

    const fetchPosts = async () => {
        try {
            const response = await axios.get('https://localhost:8000/api/posts');
            setPosts(response.data);
        } catch (error) {
            console.error('Error fetching posts:', error);
        }
    };

    const handlePostCreated = (newPost) => {
        setPosts(prevPosts => [newPost, ...prevPosts]);
    };


    return (
        <div className='postfull'>
                    {posts.map(post => (
                <PostItem key={post.id} post={post} />
            ))}
        </div>
    );
};

export default PostList;
