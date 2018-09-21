**Guest Class**

Updated Guest Class 

Approach :
 -- DbConnectionInterface for seprating the database connection
 -- A generic MySQLConnection Class which will be act as a helper class to do database all CURD operations which implemants DbConnectionInterface
 -- In Guest Class now we sending Database object as Dependacy Injection we can pass the database object in constuctor. The class is using MySQLConnection for all insert database operations.

basic usage

```
use Guest;
use MySQLConnection;

$guest = new Guest(new MySQLConnection());

$guest->addGuest(array('name'=> 'Test User', 'address' => 'My Address', 'phone' => '+1-541-754-3010', 'email' => 'test@user.com'));
```

You can also execute test by hitting

```
$ php test.php
```