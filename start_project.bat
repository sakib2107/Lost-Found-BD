@echo off
echo ============================================================
echo Starting Missing Person Finder Project
echo ============================================================
echo.
echo This will start:
echo 1. Flask API Server (port 5000)
echo 2. NPM Dev Server (Vite - for assets)
echo 3. Laravel Development Server (port 8000)
echo 4. Open browser to http://localhost:8000
echo.
echo ============================================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Python is not installed or not in PATH
    echo Please install Python from https://www.python.org/
    pause
    exit /b 1
)

REM Check if PHP is installed
php --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install PHP or add it to PATH
    pause
    exit /b 1
)

REM Check if Node.js is installed
node --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Node.js is not installed or not in PATH
    echo Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)

echo [1/5] Checking Flask dependencies...
python -c "import flask" >nul 2>&1
if errorlevel 1 (
    echo Installing Flask dependencies...
    pip install -r requirements.txt
)

echo [2/5] Starting Flask API Server...
start "Flask API - CLIP Embeddings" cmd /k "cd /d %~dp0 && echo Starting Flask API... && python clip_api.py"

echo [3/5] Waiting for Flask API to start...
timeout /t 5 /nobreak >nul

echo [4/5] Starting NPM Dev Server (Vite)...
start "NPM Dev - Vite Assets" cmd /k "cd /d %~dp0 && echo Starting Vite... && npm run dev"

echo [5/5] Waiting for Vite to start...
timeout /t 3 /nobreak >nul

echo Starting Laravel Development Server...
start "Laravel Server - Missing Person Finder" cmd /k "cd /d %~dp0 && echo Starting Laravel... && php artisan serve"

echo.
echo Waiting for Laravel to start...
timeout /t 3 /nobreak >nul

echo.
echo ============================================================
echo Project Started Successfully!
echo ============================================================
echo.
echo Flask API:     http://localhost:5000
echo Vite Dev:      http://localhost:5173
echo Laravel App:   http://localhost:8000
echo.
echo Opening browser...
echo ============================================================
echo.

REM Open browser
start http://localhost:8000

echo.
echo IMPORTANT:
echo - Three command windows have opened:
echo   1. Flask API (CLIP Embeddings)
echo   2. NPM Dev (Vite Assets)
echo   3. Laravel Server (Main App)
echo - Keep all windows open while working
echo - Close all windows when you're done
echo.
echo To stop the project:
echo 1. Close the "Flask API" window
echo 2. Close the "NPM Dev" window
echo 3. Close the "Laravel Server" window
echo.
echo ============================================================

pause
