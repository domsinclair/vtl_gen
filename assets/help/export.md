### Exporting a Database Script

Vtl Data Generator also includes the following library mysqldump.php which allows you to run a mysqldump from php.

To export the database select those tables that you wish to export by checking the relevant boxes on the lefthand side of the table. If you wish to export the data from the tables then check the relevant box io the right hand side.

The export process is highly configurable so to avoid too much only the most common settings have been applied.  However if you look at the export code in the module and consult the readme for the mysqldump.php file you will be able to see what you can configure.

> backups will be stored in the following location
> vtl_gen/vtl_faker/assets/backups   
> If that folder does not exist it will be created for you.


