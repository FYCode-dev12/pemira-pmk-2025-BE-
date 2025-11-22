#!/bin/bash

# PEMIRA PMK 2025 - Frontend Integration Copy Script
# This script copies all necessary files from backend to frontend

echo "🚀 PEMIRA PMK 2025 - Frontend Integration Setup"
echo "=============================================="
echo ""

# Set paths
BACKEND_DIR="/home/fycode/Documents/pemira-pmk-2025/pemira-pmk-2025-BE-"
FRONTEND_DIR="/home/fycode/Documents/pemira-pmk-2025/pemira-pmk-2025-FE-"
INTEGRATION_DIR="$BACKEND_DIR/frontend-integration"

# Check if frontend directory exists
if [ ! -d "$FRONTEND_DIR" ]; then
    echo "❌ Frontend directory not found: $FRONTEND_DIR"
    echo "Please make sure the frontend project exists."
    exit 1
fi

echo "✅ Frontend directory found"
echo ""

# Create directories if they don't exist
echo "📁 Creating directory structure..."
mkdir -p "$FRONTEND_DIR/src/services"
mkdir -p "$FRONTEND_DIR/src/contexts"
mkdir -p "$FRONTEND_DIR/src/hooks"
mkdir -p "$FRONTEND_DIR/src/pages"
echo "✅ Directories created"
echo ""

# Copy files
echo "📋 Copying files..."

# Environment file
if [ ! -f "$FRONTEND_DIR/.env" ]; then
    cp "$INTEGRATION_DIR/.env.example" "$FRONTEND_DIR/.env"
    echo "✅ .env file created"
else
    echo "⚠️  .env already exists, skipping (check .env.example for reference)"
fi

# Services
cp "$INTEGRATION_DIR/services/api.js" "$FRONTEND_DIR/src/services/"
echo "✅ services/api.js"

# Contexts
cp "$INTEGRATION_DIR/contexts/AuthContext.jsx" "$FRONTEND_DIR/src/contexts/"
echo "✅ contexts/AuthContext.jsx"

# Hooks
cp "$INTEGRATION_DIR/hooks/useAuth.js" "$FRONTEND_DIR/src/hooks/"
echo "✅ hooks/useAuth.js"

# Pages
cp "$INTEGRATION_DIR/pages/AdminLogin.jsx" "$FRONTEND_DIR/src/pages/"
cp "$INTEGRATION_DIR/pages/PemilihLogin.jsx" "$FRONTEND_DIR/src/pages/"
cp "$INTEGRATION_DIR/pages/Vote.jsx" "$FRONTEND_DIR/src/pages/"
cp "$INTEGRATION_DIR/pages/Results.jsx" "$FRONTEND_DIR/src/pages/"
echo "✅ All page components"

# App files
cp "$INTEGRATION_DIR/App.jsx" "$FRONTEND_DIR/src/"
echo "✅ App.jsx"

cp "$INTEGRATION_DIR/main.jsx" "$FRONTEND_DIR/src/"
echo "✅ main.jsx"

cp "$INTEGRATION_DIR/index.css" "$FRONTEND_DIR/src/"
echo "✅ index.css"

# Config files
cp "$INTEGRATION_DIR/tailwind.config.js" "$FRONTEND_DIR/"
echo "✅ tailwind.config.js"

cp "$INTEGRATION_DIR/postcss.config.js" "$FRONTEND_DIR/"
echo "✅ postcss.config.js"

echo ""
echo "=============================================="
echo "✅ All files copied successfully!"
echo ""
echo "📦 Next steps:"
echo ""
echo "1. Navigate to frontend:"
echo "   cd $FRONTEND_DIR"
echo ""
echo "2. Install dependencies:"
echo "   npm install axios react-router-dom"
echo "   npm install -D tailwindcss postcss autoprefixer"
echo ""
echo "3. Start backend (Terminal 1):"
echo "   cd $BACKEND_DIR"
echo "   php artisan serve"
echo ""
echo "4. Start frontend (Terminal 2):"
echo "   cd $FRONTEND_DIR"
echo "   npm run dev"
echo ""
echo "5. Open browser: http://localhost:5173"
echo ""
echo "🔑 Test credentials:"
echo "   Admin: admin / Admin123!"
echo "   Pemilih: 119380073 / yhwcjk"
echo ""
echo "=============================================="
