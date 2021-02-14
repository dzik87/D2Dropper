# Stats
![GitHub last commit](https://img.shields.io/github/last-commit/dzik87/D2Dropper?style=for-the-badge)
![GitHub all releases](https://img.shields.io/github/downloads/dzik87/D2Dropper/total?style=for-the-badge)
![GitHub repo size](https://img.shields.io/github/repo-size/dzik87/D2Dropper?style=for-the-badge)

# Dropper Install: 

01. Download [ItemManagerServer.zip](https://github.com/dzik87/D2Dropper/releases/download/1.0.0/ItemManagerServer.zip) and unzip everything to C drive root.
02. Navigate to C drive and install `01.vcredist_x862012.exe`
03. Open start menu type in CMD and run it as Administrator.
04. In command line console type in `cd /` and press enter
05. Type in `02.tradelistfix.bat` then enter
06. Type in `03.install.bat` then enter
07. Type in `exit` and press enter
08. Download this repository with [Download ZIP](https://github.com/dzik87/D2Dropper/archive/main.zip) option
09. Navigate to main folder select all files inside of `D2Dropper-main\html\` folder and unzip them to `C:\Apache\htdocs\`

# Adding Admin User and Resellers:
01. Navigate to [this page](http://aspirine.org/htpasswd_en.html)
02. On left side input your username and password for dropper login in following format:
	"username[onefreespace]password"
03. Use "Generate htpasswd content" on right side to generate user line
04. Copy generated content.
05. Navigate to `C:\Apache\htdocs\` and open `.htpasswd` file in notepad++
06. Each line represents one user what can log in to your dropper.
07. Paste copied content in new line.
08. Save `.htpasswd` file
09. To add resellers accounts repeat all steps.
10. Navigate to `C:\Apache\htdocs\` folder and open `config.php` file in notepad++
11. Define your self as Admin
12. Add your self in `$authorized` list.
13. Decide how many dropper profiles you want to use for dropping items. (ussually good choice is between 2 and 4 profiles)
14. If you want add resellers add , on end of line and press CTRL+G to clone line.
15. Input reseller name in cloned line.
16. Adjust dropper profiles for resellers if needed..
17. Save `config.php` file.

# Testing Page:
01. Click [here](http://127.0.0.1:666/)
02. Use your login credentials to log in into dropper page.
03. You should see now your dropper page and be able to use Drop, TradeList and Admin menu.

# Setting Up Kolbot for Droppers:
01. Download latest kolbot from https://github.com/blizzhackers/kolbot
02. Copy and replace files from `D2Dropper-main\kolbot\` subfolder to `d2bs\kolbot\`
03. Navigate to `d2bs\kolbot\` and run `link.bat` as Admin
04. This will create folder called `databases`
05. Inside this folder you will see exactly same content as in `C:\Apache\htdocs\`
06. Start D2Bot
07. Create New profiles in D2Bot and name them in same way as you defined them in `config.php`
08. Default names are `dropper1`, `dropper2` and so on.
09. Use `D2BotDropper.dbj` as starter file.
10. Setup as many profiles as you defined in `config.php`
11. It is important to have same ammount of profiles in D2Bot and in config.php to avoid issues.
### YOU MUST HAVE ALL DEFINED DROPPER PROFILES ALWAYS RUNNING IN D2BOT

# Logging existing mules to dropper page:
01. After those steps all items Automuled since now will be added automatically to page.
02. To add all mules what you had BEFORE installing dropper you need to use MuleLogger.
03. Instruction how to use MuleLogger can be found inside `MuleLogger.js` file inside `d2bs\kolbot\libs\` folder.
04. Starter used to log items must be `D2BotMuleLog.dbj`

# Multiple Bot folders:
01. If you use more than one folder to bot you can make all of them log items to database.
02. Instead of copying all files from kolbot to your bot folder you are required to copy only couple:
	- `d2bs\kolbot\link.bat`
	- `d2bs\kolbot\D2BotMule.dbj`
	- `d2bs\kolbot\libs\ItemDB.js`
	- `d2bs\kolbot\libs\MuleLogger.js`
03. Then run `d2bs\kolbot\link.bat` as Administrator to create `databases` folder.
04. This will make your current kolbot instance to log into global database

# Dropping Items:
01. Click [here](http://127.0.0.1:666/) to open your local dropper webpage
02. Log in.
03. Pick item(s) what you want to drop.
04. Use DROP menu to define game name and password.
05. Type in value (must be more than 0) and use drop button.

### Enjoy your personal dropper.

# After words
This is my work.
I make it public because its right thing to do.

Code is provided as it is.
Feel free to contribute.

[![Discord](https://img.shields.io/badge/Discord-dzik%232463-orange.svg?style=for-the-badge&logo=discord)](https://discordapp.com/users/209047731996590080)
[![Donate](https://img.shields.io/badge/PayPal-Donate-blue.svg?style=for-the-badge&logo=paypal)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KWYLPLQXAAQKS)

### dzik © 2021
