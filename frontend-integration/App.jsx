import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import { useAuth } from './hooks/useAuth';

// Pages
import AdminLogin from './pages/AdminLogin';
import PemilihLogin from './pages/PemilihLogin';
import Vote from './pages/Vote';
import Results from './pages/Results';

// Protected Route Component
const ProtectedRoute = ({ children, requireAdmin = false }) => {
  const { isAuthenticated, isAdmin, loading } = useAuth();

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  if (!isAuthenticated) {
    return <Navigate to="/login/pemilih" replace />;
  }

  if (requireAdmin && !isAdmin) {
    return <Navigate to="/vote" replace />;
  }

  return children;
};

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          {/* Public Routes */}
          <Route path="/" element={<Navigate to="/login/pemilih" replace />} />
          <Route path="/login/admin" element={<AdminLogin />} />
          <Route path="/login/pemilih" element={<PemilihLogin />} />

          {/* Protected Routes */}
          <Route
            path="/vote"
            element={
              <ProtectedRoute>
                <Vote />
              </ProtectedRoute>
            }
          />
          
          <Route
            path="/results"
            element={
              <ProtectedRoute requireAdmin>
                <Results />
              </ProtectedRoute>
            }
          />

          {/* 404 */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;
