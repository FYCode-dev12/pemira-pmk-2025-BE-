import axios from 'axios';

// Create axios instance
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor - Add token to requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor - Handle errors globally
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('role');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  loginAdmin: (credentials) => api.post('/auth/admin/login', credentials),
  loginSuperAdmin: (credentials) => api.post('/auth/super-admin/login', credentials),
  loginPemilih: (credentials) => api.post('/auth/pemilih/login', credentials),
  logout: () => api.post('/auth/logout'),
};

// Kandidat API
export const kandidatAPI = {
  getAll: () => api.get('/kandidat'),
};

// Vote API
export const voteAPI = {
  submit: (kandidatId) => api.post('/vote', { kandidat_id: kandidatId }),
  checkStatus: () => api.get('/vote/status'),
};

// Results API
export const resultsAPI = {
  getSummary: () => api.get('/results/summary'),
};

export default api;
