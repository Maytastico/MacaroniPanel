**Description**<br>
This is an application for people who want to easily modify content on their website or manage certain applications on their server.
<br>
This Panel will be able to add user roles and modules, so everyone is able to customize this application for his needs.
<br>
It will come with a permission system, that makes it easy to specify the rights of an user.

**Install**<br>
*Permissions*<br>
You will have to modify the permissions on an unix system, because you won't be able to execute the php scripts.
The command **chmod -R * 755** should help. Not recommended is to use **chmod -R * 777**, but it can be a quick fix in 
some cases. 
<br><br>
*Enviroment*<br>
Installing this application on a webserver is fairly simple.
At first you have to install a SQL Server, a Webserver (e.q Apache) and php with an mysql extension.
A short description for this is LAMP.
I recommend to install [phpmyadmin](https://www.phpmyadmin.net/) for easier maintenance.
If you want to use this dashboard on a Windows machine, you should install [XAMMP](https://www.apachefriends.org).<br>
It is easy to configure and easy to use. <br>
Do you want to use this application on a Linux Machine?<br>
That isn't a problem a well. However I won't go into detail on how to install these things listed above.
There are bunche of tutorials on how to install a LAMP environment.<br>

Quick guides for LAMP:<br>
* For [Ubuntu](https://www.linode.com/docs/web-servers/lamp/install-lamp-stack-on-ubuntu-18-04/)<br>
* For [Debian](https://www.linuxbabe.com/debian/install-lamp-stack-debian-9-stretch)<br><br>

Quick guides for phpmyadmin:<br>
* For [Ubuntu](https://www.hostingadvice.com/how-to/install-phpmyadmin-on-ubuntu/)<br>
* For [Debian](https://tecadmin.net/install-phpmyadmin-on-debian/)

*Database*<br>
If everything is ready, you will be able to add a database.
I will explain it with phpmyadmin.<br>
The first step is to log into your phpmyadmin account.
You will see a menu on the left of your screen. There will be a button with the lable "New".
Press it and a dialog will open on the right. Now put a name into the input field.
I have used dashboard as a database name, you should use it as well.
If the database was added, you should go to the directory where the dashboard is located.

Head to **/assets/php** and open **config.php** in an editor.
Now put your database username and database password into **$pdoUser** and **$pdoPW**.
If you have a database that is not located on you Computer, you will have edit a few things in **$pdoDNS**.


*Installing Panel*<br>
If everything is right, you will be able to install the tables and add a user.
Open **/install** on your internet browser and press **"Install Tables"**.
Now you can add a new account to the dashboard!

The next step is to login. Press **"Login"** and you will be redirected to a login mask, where you can put in your login information.