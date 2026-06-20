@echo off
cd /d "%~dp0"
taskkill /F /IM node.exe >nul 2>&1
timeout /t 1 /nobreak >nul
set CLAUDE_API_KEY=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
start "" node server.js
timeout /t 1 /nobreak >nul
start "" http://localhost:3000
