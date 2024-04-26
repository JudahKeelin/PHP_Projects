In order to allow communication with the Azure SQL Server you need to add both of the .dll files in this folder to the xampp/php/ext folder. Then in your xampp/php folder find the php.ini file and the the following lines somewhere in the text file:

extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv.dll

These will enable the extensions

You will need to create a database using the .bacpac file in this folder

After that you will need to replace the connection string on line 4 of the dbh.inc.php file with the one for your server

Hardware Stores currently need to be created manually in the db and you can set the managerId to the person you want to be the manager's People.id
If you wish to be a manager or admin that also needs to be set manually in the db with the userLevel Attribute

User Level 0 = Admin
User Level 1 = Manager
User Level 2 = Shopper