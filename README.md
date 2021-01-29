# Dropper Install: 

01. Open 1.ItemManagerServer.zip and unzip everything to C drive.
02. Navigate to C drive and install 01.vcredist_x862012.exe
03. Open start menu type in CMD and run it as Administrator.
04. on CMD consoole type in "cd \"
05. type in "02", press tab then enter
06. type in "03", press tab then enter
07. type in "exit" and press enter
08. Open 2.Missing.zip file and copy both files to C:\Apache\htdocs folder.
09. Open 2017.01.XX-ItemManagerUpdate.zip file
10. Navigate to htdocs select all files inside and unzip them to C:\Apache\htdocs

# Adding Admin User and Resellers:
01. Open CHROME BROWSER and open this page: http://aspirine.org/htpasswd_en.html
02. On left side input your username and password for dropper login in following format:
	"username[onefreespace]password"
03. Use "Generate htpasswd content" on right side to generate user line
04. Copy generated content.
05. Navigate to C:\Apache\htdocs and open ".htpasswd" file in notepad++
06. Each line represents one user what can log in to your dropper.
07. Paste copied content in new line.
08. Save ".htpasswd" file
09. To add resellers accounts repeat all steps.
10. Navigate to C:\Apache\htdocs folder and open "config.php" file in notepad++
11. Define your self as Admin
12. Add your self in $authorized list.
13. Decide how many dropper profiles you want to use for dropping items. (ussually good choice is between 2 and 4 profiles)
14. If you want add resellers add , on end of line and press CTRL+G to clone line.
15. Input reseller name in cloned line.
16. Save "config.php" file.

# Testing Page:
01. Open CHROME BROWSER and type in http://localhost:666
02. Use your credentials and log in to dropper page.
03. You should see now your dropper page and can use Drop, TradeList and Admin menu.

# Installing Dropper on Kolbot.
01. Open 2017.01.XX-ItemManagerUpdate.zip file
02. Navigate to kolbot folder inside and copy everything to your bot folder overwriting everything.
03. In your bot in main kolbot folder is file called "link.bat"
04. Run "link.bat" as Administrator
05. It will create folder called "databases"
06. Inside tis file you will see exactly same content as in C:\Apache\htdocs

# Setting Up Kolbot for Droppers:
01. Open your kolbot folder and start D2Bot.
02. Create New profiles in D2Bot and name them in same way as you defined them in config.php
03. Default names are "dropper1", "dropper2" and so on.
04. Use D2BotDropper.dbj as starter file.
05. Setup as many profiles as you defined in config.php
06. It is important to have same ammount of profiles in D2Bot and in config.php to avoid issues.

# Logging existing mules to dropper page:
01. After those steps all items Automuled since now will be added automatically to page.
02. To add all mules what you had BEFORE installing dropper you need to use MuleLogger.
03. Instruction how to use MuleLogger can be found inside MuleLogger.js file inside Libs folder.
04. Starter Used to log items must be D2BotMuleLog.dbj

# Multiple Bot folders:
01. If you use more than one folder to bot you can make all of them log items to database.
02. Instead of copying all files from ZIP to bot folder you are required to copy only couple:
	- kolbot/link.bat
	- kolbot/D2BotMule.dbj
	- kolbot/libs/ItemDB.js
	- kolbot/libs/MuleLogger.js
03. Then run "link.bat" as Administrator to create databases folder.

# AutoPlay Bot and Logging Items:
01. NEVER use AP folder for dropper profiles.
02. To log items from AP bots you need copy just same files as for normal bot:
	- kolbot/link.bat
	- kolbot/D2BotMule.dbj
	- kolbot/libs/ItemDB.js
	- kolbot/libs/MuleLogger.js
03. Then run "link.bat" as Administrator to create databases folder.

# Dropping Items:
01. In CHROME BROWSER navigate to http://localhost:666
02. Log in.
03. Pick item(s) what you want to drop.
04. Use DROP menu to define game name and password.
05. Type in value (must be more than 0) and use drop button.

!!! YOU MUST HAVE ALL DEFINED DROPPER PROFILES ALWAYS RUNNING IN D2BOT !!!

Enjoy your personal dropper.
	
