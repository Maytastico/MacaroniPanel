#Description##<br>
This is an application for people who want to modify content on their website or manage certain applications on their server.
It comes with a simple permission system that is fully customizable. There is an administration panel for users, that is able to search, add, delete and edit
your users as an administrator. There will come an administration panel for the permission system as well.
A normal is just able to edit its own information as well as its profile picture yet.  
<br>
The next stage in this learning project will be a module system that adds more functions to this application.

#Install##<br>

##File and executing permissions##<br>
You will have to modify the permissions of the php-scripts on an unix system or you won't be able to execute the php scripts.
The command **chmod -R * 755** should help. Not recommended is to use **chmod -R * 777**, but it can be a quick fix in 
some cases. 
<br><br>
##Enviroment##<br>
Installing this application on a webserver is fairly simple.
At first you have to install a SQL Server, a Webserver (e.q Apache) and php with an php-mysql extension.
A short description for this is LAMP.
I recommend to install [phpmyadmin](https://www.phpmyadmin.net/) for easier maintenance.

If you want to use this dashboard on a Windows machine, you should install [XAMMP](https://www.apachefriends.org).<br>
It is easy to configure and easy to use. <br>

Do you want to use this application on a Linux Machine?<br>
That isn't a problem a well. However I won't go into detail on how to install these things listed above.
There are bunche of tutorials on how to install a LAMP environment.<br>

**Quick guides for LAMP:**<br>
* For [Ubuntu](https://www.linode.com/docs/web-servers/lamp/install-lamp-stack-on-ubuntu-18-04/)<br>
* For [Debian](https://www.linuxbabe.com/debian/install-lamp-stack-debian-9-stretch)<br><br>

**Quick guides for phpmyadmin:**<br>
* For [Ubuntu](https://www.hostingadvice.com/how-to/install-phpmyadmin-on-ubuntu/)<br>
* For [Debian](https://tecadmin.net/install-phpmyadmin-on-debian/)

##Database##<br>
If everything is ready, you will be able to add a database.
I will explain it with phpmyadmin.<br>
The first step is to log into your phpmyadmin account.
You will see a menu on the left of your screen. There will be a button that is labeled with **"New"**.
Press it and a dialog will open on the right. Now put a name into the input field.
I have used **"dashboard"** as a database name, you should use it as well.
If the database was added, you should go to the directory where the dashboard is located.

Head to **/assets/php** and open **config.php** in an editor.
Now put your database username and database password into **$pdoUser** and **$pdoPW**.
If you have a database that is not located on you Computer, you will have edit a few things in **$pdoDNS**.

This is an example for how to edit the content for this variable

Domain       | $pdoDNS
------------ | -------------
localhost    | mysql:host=localhost;port=3306;dbname=dashboard
domain.eu    |  mysql:host=domain.eu;port=3306;dbname=dashboard
192.168.2.1  | mysql:host=192.168.2.1;port=3306;dbname=dashboard


##Installing Panel##<br>
![installation](https://raw.githubusercontent.com/MacaroniDamage/macaronipanel-development/master/img/installation.jpg "Logo Title")

<br>
If everything went good, you will be able to see the the install page above.
Type the destination of the panel into internet browser and append install  at the end.

###Install###
1. Step: Confirming installation. 
    - Press the checkbox, so the script knows that you are sure whether you want to install the table inside the database.

2. Step: Install tables.
    - Press the button that says **Install Tables**
    
3. Step: Type in your account credentials.
    - Note that your username should be called "admin" and that your password is longer than 8 characters.
    - Remember your account information
    
4. Step: Submit form.

5. Step: Press the login button.

6. Step: Enter login credentials.
