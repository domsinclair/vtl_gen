### Deleting Data

Deleting data is a pretty straightforward operation.

Simply select those tables for which you wish to delete data and then click the 'Clear Data' button. You will then see a
modal detailing what has happened.

> By default, the following tables will have all but the first row of data deleted so as to retain a default Trongate
> installation;
>  - trongate_administrators
>  - trongate_users
>  - trongate_user_levels

You can also opt to delete data from a table and reset the auto increment.  To trigger that option you should check the 'Reset Primary Auto Increment' at the bottom of the list of tables.  This invokes a Truncate command dropping and resetting the table.  it is a much quicker way to delete large tables.  Because of the fact that you may need to retain certain Trongate security information this option will not be applied to the following tables;

- trongate_administrators
- trongate_users
- trongate_user_levels
