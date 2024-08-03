// src/api.js
import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000', // Remplacez par l'URL de votre API Symfony
});

export default api;
