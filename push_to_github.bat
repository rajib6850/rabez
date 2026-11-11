@echo off
title RABEZ - Push to GitHub
cd /d "e:\My Busniess\rabez\website design"
powershell -NoProfile -ExecutionPolicy Bypass -File "push_to_github.ps1"
pause
