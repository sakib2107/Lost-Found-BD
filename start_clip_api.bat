@echo off
echo ============================================================
echo Starting CLIP Embedding API Server
echo ============================================================
echo.
echo This will start the Flask API server that connects to
echo Hugging Face Space for CLIP embedding generation.
echo.
echo Server will run on: http://localhost:5000
echo.
echo Press Ctrl+C to stop the server
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

REM Check if required packages are installed
echo Checking dependencies...
python -c "import flask" >nul 2>&1
if errorlevel 1 (
    echo.
    echo ERROR: Flask is not installed
    echo Installing required packages...
    pip install -r requirements.txt
    if errorlevel 1 (
        echo.
        echo ERROR: Failed to install dependencies
        pause
        exit /b 1
    )
)

python -c "import gradio_client" >nul 2>&1
if errorlevel 1 (
    echo.
    echo ERROR: gradio_client is not installed
    echo Installing required packages...
    pip install -r requirements.txt
    if errorlevel 1 (
        echo.
        echo ERROR: Failed to install dependencies
        pause
        exit /b 1
    )
)

echo Dependencies OK!
echo.
echo Starting Flask API server...
echo.

REM Start the Flask API
python clip_api.py

pause
