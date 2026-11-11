# RABEZ GitHub Auto-Push Script
$env:Path = "C:\Program Files\GitHub CLI;C:\Program Files\Git\cmd;" + $env:Path
Set-Location -Path "e:\My Busniess\rabez\website design"

Write-Host "==========================================================" -ForegroundColor Green
Write-Host "         RABEZ -> GITHUB AUTO SETUP & PUSH               " -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Green
Write-Host ""

# 1. Check gh auth status
$authCheck = & "C:\Program Files\GitHub CLI\gh.exe" auth status 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "[1/2] GitHub-e login kora nai. Browser open hocche login korar jonno..." -ForegroundColor Yellow
    Write-Host "Browser-e 'Authorize github' click korun..." -ForegroundColor Cyan
    & "C:\Program Files\GitHub CLI\gh.exe" auth login --web -p https
} else {
    Write-Host "[1/2] GitHub-e already login kora ache!" -ForegroundColor Green
}

Write-Host ""
Write-Host "[2/2] GitHub repository create ebong code push kora hocche..." -ForegroundColor Yellow

# Try creating the repository if it doesn't exist
$repoCreate = & "C:\Program Files\GitHub CLI\gh.exe" repo create rabez --public --source="." --remote="origin" --push 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "Repository already exists or remote is set. Running direct git push..." -ForegroundColor Cyan
    git push -u origin main
}

Write-Host ""
if ($LASTEXITCODE -eq 0) {
    Write-Host "==========================================================" -ForegroundColor Green
    Write-Host "  SUCCESS! RABEZ project successfully pushed to GitHub!   " -ForegroundColor Green
    Write-Host "==========================================================" -ForegroundColor Green
} else {
    Write-Host "Push complete or check output above." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Press any key to close this window..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
