import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { kandidatAPI, voteAPI } from '../services/api';
import { useAuth } from '../hooks/useAuth';

const Vote = () => {
  const [kandidat, setKandidat] = useState([]);
  const [selectedKandidat, setSelectedKandidat] = useState(null);
  const [voteStatus, setVoteStatus] = useState(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      setLoading(true);
      
      // Check vote status
      const statusRes = await voteAPI.checkStatus();
      setVoteStatus(statusRes.data);

      // Get kandidat list
      const kandidatRes = await kandidatAPI.getAll();
      setKandidat(kandidatRes.data.data);
      
      setLoading(false);
    } catch (err) {
      setError('Gagal memuat data');
      setLoading(false);
    }
  };

  const handleVote = async () => {
    if (!selectedKandidat) {
      setError('Pilih kandidat terlebih dahulu');
      return;
    }

    if (window.confirm('Apakah Anda yakin dengan pilihan Anda? Pilihan tidak dapat diubah.')) {
      try {
        setSubmitting(true);
        setError('');
        
        await voteAPI.submit(selectedKandidat);
        
        setSuccess('Suara berhasil disimpan! Terima kasih telah berpartisipasi.');
        
        // Refresh status
        setTimeout(() => {
          fetchData();
        }, 2000);
        
      } catch (err) {
        setError(err.response?.data?.message || 'Gagal menyimpan suara');
        setSubmitting(false);
      }
    }
  };

  const handleLogout = async () => {
    await logout();
    navigate('/login/pemilih');
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

  if (voteStatus?.sudah_memilih) {
    return (
      <div className="min-h-screen bg-gray-100 py-8">
        <div className="max-w-2xl mx-auto px-4">
          <div className="bg-white p-8 rounded-lg shadow-md text-center">
            <div className="text-green-500 text-6xl mb-4">✓</div>
            <h2 className="text-2xl font-bold mb-4">Anda Sudah Memilih</h2>
            <p className="text-gray-600 mb-2">
              Terima kasih telah berpartisipasi dalam PEMIRA PMK 2025!
            </p>
            <p className="text-sm text-gray-500">
              Waktu memilih: {new Date(voteStatus.waktu_memilih).toLocaleString('id-ID')}
            </p>
            <button
              onClick={handleLogout}
              className="mt-6 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
              Logout
            </button>
          </div>
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
              <h1 className="text-2xl font-bold">PEMIRA PMK 2025</h1>
              <p className="text-gray-600">Selamat datang, {user?.nama}</p>
            </div>
            <button
              onClick={handleLogout}
              className="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
            >
              Logout
            </button>
          </div>
        </div>

        {/* Messages */}
        {error && (
          <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {error}
          </div>
        )}

        {success && (
          <div className="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {success}
          </div>
        )}

        {/* Instructions */}
        <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
          <p className="text-sm text-yellow-700">
            <strong>Perhatian:</strong> Pilih salah satu kandidat di bawah ini. Setelah memilih, keputusan tidak dapat diubah.
          </p>
        </div>

        {/* Kandidat List */}
        {kandidat.length === 0 ? (
          <div className="bg-white p-8 rounded-lg shadow-md text-center">
            <p className="text-gray-600">Belum ada kandidat yang terdaftar.</p>
          </div>
        ) : (
          <div className="grid md:grid-cols-2 gap-6 mb-6">
            {kandidat.map((k) => (
              <div
                key={k.id}
                onClick={() => setSelectedKandidat(k.id)}
                className={`bg-white p-6 rounded-lg shadow-md cursor-pointer transition-all ${
                  selectedKandidat === k.id
                    ? 'ring-4 ring-blue-500 transform scale-105'
                    : 'hover:shadow-lg'
                }`}
              >
                <div className="text-center">
                  <div className="text-4xl font-bold text-blue-500 mb-4">
                    {k.nomor_urut}
                  </div>
                  
                  <h3 className="text-xl font-bold mb-2">{k.nama_ketua}</h3>
                  <p className="text-gray-600 mb-4">{k.nama_wakil}</p>
                  
                  <div className="text-left mt-4">
                    <h4 className="font-semibold mb-2">Visi:</h4>
                    <p className="text-sm text-gray-600 mb-3">{k.visi}</p>
                    
                    <h4 className="font-semibold mb-2">Misi:</h4>
                    <p className="text-sm text-gray-600">{k.misi}</p>
                  </div>
                  
                  {selectedKandidat === k.id && (
                    <div className="mt-4 text-blue-500 font-semibold">
                      ✓ Dipilih
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Submit Button */}
        {kandidat.length > 0 && (
          <div className="bg-white p-6 rounded-lg shadow-md">
            <button
              onClick={handleVote}
              disabled={!selectedKandidat || submitting}
              className="w-full py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:bg-gray-400 font-semibold text-lg"
            >
              {submitting ? 'Menyimpan...' : 'SUBMIT VOTE'}
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Vote;
