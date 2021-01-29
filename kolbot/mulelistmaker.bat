@echo off
set /p pass=Password? 
for /D %%A IN ("./mules/*") DO (
	echo /* %%A */>>output.txt
	for /D %%B IN ("./mules/%%A/*") DO (
		echo "%%B/%pass%/%%A": ["all"],>>output.txt
	)
)