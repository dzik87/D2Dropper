@setlocal enableextensions
@cd /d "%~dp0"
del databases
mklink /D databases "C:\Apache\htdocs"