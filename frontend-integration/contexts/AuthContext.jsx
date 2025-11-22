import React, { createContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

export const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [role, setRole] = useState(null);
  const [token, setToken] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Check if user is already logged in
    const storedToken = localStorage.getItem('token');
    const storedUser = localStorage.getItem('user');
    const storedRole = localStorage.getItem('role');

    if (storedToken && storedUser && storedRole) {
      setToken(storedToken);
      setUser(JSON.parse(storedUser));
      setRole(storedRole);
    }
    setLoading(false);
  }, []);

  const login = async (type, credentials) => {
    try {
      let response;
      
      switch (type) {
        case 'admin':
          response = await authAPI.loginAdmin(credentials);
          break;
        case 'super-admin':
          response = await authAPI.loginSuperAdmin(credentials);
          break;
        case 'pemilih':
          response = await authAPI.loginPemilih(credentials);
          break;
        default:
          throw new Error('Invalid login type');
      }

      const { token, admin, pemilih, role } = response.data;
      const userData = admin || pemilih;

      // Store in localStorage
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(userData));
      localStorage.setItem('role', role);

      // Update state
      setToken(token);
      setUser(userData);
      setRole(role);

      return { success: true, role };
    } catch (error) {
      console.error('Login error:', error);
      return { 
        success: false, 
        error: error.response?.data?.message || 'Login gagal' 
      };
    }
  };

  const logout = async () => {
    try {
      await authAPI.logout();
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Clear storage and state
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('role');
      setToken(null);
      setUser(null);
      setRole(null);
    }
  };

  const value = {
    user,
    role,
    token,
    loading,
    login,
    logout,
    isAuthenticated: !!token,
    isAdmin: role === 'admin' || role === 'super-admin',
    isPemilih: role === 'pemilih',
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
