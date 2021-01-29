# Dropper Install: 

01. Download [ItemManagerServer.zip](https://github.com/dzik87/D2Dropper/releases/download/1.0.0/ItemManagerServer.zip) and unzip everything to C drive.
02. Navigate to C drive and install 01.vcredist_x862012.exe
03. Open start menu type in CMD and run it as Administrator.
04. on CMD consoole type in `cd /`
05. type in `02` then press tab and enter
06. type in `03` then press tab and enter
07. type in "exit" and press enter
08. Download this repository with [Download ZIP](https://github.com/dzik87/D2Dropper/archive/main.zip) option
09. Navigate to main folder select all files inside and unzip them to `C:\Apache\htdocs`

# Adding Admin User and Resellers:
01. Navigate to [this page](http://aspirine.org/htpasswd_en.html)
02. On left side input your username and password for dropper login in following format:
	"username[onefreespace]password"
03. Use "Generate htpasswd content" on right side to generate user line
04. Copy generated content.
05. Navigate to `C:\Apache\htdocs` and open `.htpasswd` file in notepad++
06. Each line represents one user what can log in to your dropper.
07. Paste copied content in new line.
08. Save `.htpasswd` file
09. To add resellers accounts repeat all steps.
10. Navigate to `C:\Apache\htdocs` folder and open `config.php` file in notepad++
11. Define your self as Admin
12. Add your self in `$authorized` list.
13. Decide how many dropper profiles you want to use for dropping items. (ussually good choice is between 2 and 4 profiles)
14. If you want add resellers add , on end of line and press CTRL+G to clone line.
15. Input reseller name in cloned line.
16. Adjust dropper profiles for resellers if needed..
17. Save `config.php` file.

# Testing Page:
01. Click [here](http://localhost:666)
02. Use your login credentials to log in into dropper page.
03. You should see now your dropper page and be able to use Drop, TradeList and Admin menu.

# Setting Up Kolbot for Droppers:
01. Download kolbot from [here](https://github.com/dzik87/D2Dropper/releases/download/1.0.0/kolbot-master.zip)
02. Navigate to `d2bs\kolbot\` and run `link.bat` as Admin
03. This will create folder called `databases`
04. Inside this folder you will see exactly same content as in `C:\Apache\htdocs`
05. Start D2Bot
06. Create New profiles in D2Bot and name them in same way as you defined them in `config.php`
07. Default names are `dropper1`, `dropper2` and so on.
08. Use `D2BotDropper.dbj` as starter file.
09. Setup as many profiles as you defined in `config.php`
10. It is important to have same ammount of profiles in D2Bot and in config.php to avoid issues.
### YOU MUST HAVE ALL DEFINED DROPPER PROFILES ALWAYS RUNNING IN D2BOT

# Logging existing mules to dropper page:
01. After those steps all items Automuled since now will be added automatically to page.
02. To add all mules what you had BEFORE installing dropper you need to use MuleLogger.
03. Instruction how to use MuleLogger can be found inside `MuleLogger.js` file inside `libs` folder.
04. Starter used to log items must be `D2BotMuleLog.dbj`

# Multiple Bot folders:
01. If you use more than one folder to bot you can make all of them log items to database.
02. Instead of copying all files from kolbot to your bot folder you are required to copy only couple:
	- `kolbot/link.bat`
	- `kolbot/D2BotMule.dbj`
	- `kolbot/libs/ItemDB.js`
	- `kolbot/libs/MuleLogger.js`
03. Then run `link.bat` as Administrator to create `databases` folder.
04. This will make your current kolbot instance to log into global database

# Dropping Items:
01. Click [here](http://localhost:666) to open your local dropper webpage
02. Log in.
03. Pick item(s) what you want to drop.
04. Use DROP menu to define game name and password.
05. Type in value (must be more than 0) and use drop button.

### Enjoy your personal dropper.

# Explaination word
This is my work.
I make it public because its right thing to do.
LAZ sucks.

Code is provided as it is.
Feel free to contribute.

My Discord username: `dzik#2463`

### dzik © 2021
