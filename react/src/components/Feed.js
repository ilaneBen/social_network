// src/js/components/Feed.js

import React, { useEffect, useState } from 'react';
import axios from 'axios';
import PostItem from './PostItem';

const Feed = () => {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchPosts = async () => {
            try {
                const response = await axios.get('https://localhost:8000/api/feed');
                setPosts(response.data);
                setLoading(false);
            } catch (error) {
                console.error('Error fetching feed:', error);
                setLoading(false);
            }
        };

        fetchPosts();
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    return (
        <div>
            {posts.map((post) => (
                <PostItem key={post.id} post={post} />
            ))}
        </div>
    );
};

export default Feed;
