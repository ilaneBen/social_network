import React, { useState } from 'react';
import axios from 'axios';

const PostForm = ({ userId, onPostCreated }) => {
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');

    const handleSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('https://localhost:8000/api/posts/create', { title, content, userId });
            onPostCreated(response.data);
            setTitle('');
            setContent('');
            window.location.reload()
        } catch (error) {
            console.error('Error creating post:', error);
        }
    };
            console.log(userId);

    return (
        <div className="postdiv">
        <form className='postform' onSubmit={handleSubmit}>
            <input
                type="text"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
                placeholder="Titre"
            />
            <textarea
                value={content}
                onChange={(e) => setContent(e.target.value)}
                placeholder="Quoi de neuf ?"
                required
            />
            <button type="submit">Publier</button>
        </form>
        </div>
    );
};

export default PostForm;
