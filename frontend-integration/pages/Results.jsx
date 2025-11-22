import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { resultsAPI } from '../services/api';
import { useAuth } from '../hooks/useAuth';

const Results = () => {
  const [results, setResults] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [autoRefresh, setAutoRefresh] = useState(true);
  
  const { logout, isAdmin } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (!isAdmin) {
      navigate('/login/admin');
      return;
    }

    fetchResults();

    // Auto refresh every 5 seconds
    const interval = setInterval(() => {
      if (autoRefresh) {
        fetchResults();
      }
    }, 5000);

    return () => clearInterval(interval);
  }, [autoRefresh, isAdmin]);

  const fetchResults = async () => {
    try {
      const response = await resultsAPI.getSummary();
      setResults(response.data.data);
      setLoading(false);
      setError('');
    } catch (err) {
      setError('Gagal memuat hasil');
      setLoading(false);
    }
  };

  const handleLogout = async () => {
    await logout();
    navigate('/login/admin');
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
          <p className="mt-4 text-gray-600">Loading...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 py-8">
      <div className="max-w-6xl mx-auto px-4">
        {/* Header */}
        <div className="bg-white p-6 rounded-lg shadow-md mb-6">
          <div className="flex justify-between items-center">
            <div>
              <h1 className="text-2xl font-bold">Hasil PEMIRA PMK 2025</h1>
              <p className="text-gray-600">Real-time voting results</p>
            </div>
            <div className="flex gap-3">
              <button
                onClick={() => setAutoRefresh(!autoRefresh)}
                className={`px-4 py-2 rounded ${
                  autoRefresh
                    ? 'bg-green-500 text-white'
                    : 'bg-gray-300 text-gray-700'
                }`}
              >
                {autoRefresh ? '🔄 Auto Refresh ON' : 'Auto Refresh OFF'}
              </button>
              <button
                onClick={handleLogout}
                className="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
              >
                Logout
              </button>
            </div>
          </div>
        </div>

        {error && (
          <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {error}
          </div>
        )}

        {/* Statistics Summary */}
        <div className="grid md:grid-cols-4 gap-4 mb-6">
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-gray-600 text-sm mb-2">Total Pemilih</div>
            <div className="text-3xl font-bold text-blue-500">
              {results?.total_pemilih?.toLocaleString()}
            </div>
          </div>
          
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-gray-600 text-sm mb-2">Sudah Memilih</div>
            <div className="text-3xl font-bold text-green-500">
              {results?.total_sudah_memilih?.toLocaleString()}
            </div>
          </div>
          
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-gray-600 text-sm mb-2">Belum Memilih</div>
            <div className="text-3xl font-bold text-orange-500">
              {results?.total_belum_memilih?.toLocaleString()}
            </div>
          </div>
          
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-gray-600 text-sm mb-2">Partisipasi</div>
            <div className="text-3xl font-bold text-purple-500">
              {results?.persentase_partisipasi?.toFixed(1)}%
            </div>
          </div>
        </div>

        {/* Kandidat Results */}
        <div className="bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-xl font-bold mb-6">Perolehan Suara Kandidat</h2>
          
          {results?.kandidat?.length === 0 ? (
            <p className="text-gray-600 text-center py-8">
              Belum ada kandidat yang terdaftar
            </p>
          ) : (
            <div className="space-y-6">
              {results?.kandidat
                ?.sort((a, b) => b.total_suara - a.total_suara)
                .map((k, index) => (
                  <div key={k.id} className="border-b pb-4 last:border-b-0">
                    <div className="flex items-center justify-between mb-2">
                      <div className="flex items-center gap-4">
                        <div className="text-2xl font-bold text-gray-400 w-8">
                          #{index + 1}
                        </div>
                        <div>
                          <div className="font-semibold text-lg">
                            Nomor Urut {k.nomor_urut}
                          </div>
                          <div className="text-gray-600">
                            {k.nama_ketua} & {k.nama_wakil}
                          </div>
                        </div>
                      </div>
                      <div className="text-right">
                        <div className="text-3xl font-bold text-blue-500">
                          {k.total_suara.toLocaleString()}
                        </div>
                        <div className="text-sm text-gray-600">
                          {k.persentase.toFixed(1)}%
                        </div>
                      </div>
                    </div>
                    
                    {/* Progress Bar */}
                    <div className="w-full bg-gray-200 rounded-full h-3 mt-3">
                      <div
                        className="bg-blue-500 h-3 rounded-full transition-all duration-500"
                        style={{ width: `${k.persentase}%` }}
                      ></div>
                    </div>
                  </div>
                ))}
            </div>
          )}
        </div>

        {/* Footer Info */}
        <div className="mt-6 text-center text-sm text-gray-600">
          <p>
            Last updated: {new Date().toLocaleString('id-ID')}
          </p>
          {autoRefresh && (
            <p className="text-green-600">
              Auto refresh aktif - data diperbarui setiap 5 detik
            </p>
          )}
        </div>
      </div>
    </div>
  );
};

export default Results;
