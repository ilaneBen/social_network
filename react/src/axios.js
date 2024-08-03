// src/axios.js
import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost:8000', // Adresse de votre API Symfony
});

export default instance;
