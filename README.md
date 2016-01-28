MToolkit - Model
================
The model module of [MToolkit](https://github.com/mtoolkit/mtoolkit) framework.

# Summary
- [Intro](#intro)

#<a name="intro"></a>Intro
The module provides the classes to represent x-dimensional models, as list or table.
There are a submodule about the sql models: to run a query, to retrieve a resultset, etc...

## MDbConnection
It provides a simple singleton way to store the connection to the database.
Usages:
```php
// Add a new connection string
MDbConnection::addDbConnection( new \PDO( 'mysql:host=127.0.0.1;dbname=test_db', 'root', 'password' ) );

// Retrieve the database connection
$connection = MDbConnection::getDbConnection();
```

## MPDOQuery and MPDOResult
Usages:
```php
$query = "SELECT item_01 FROM table_01";
$connection = MDbConnection::getDbConnection();
$sql = new MPDOQuery( $query, $connection );
$sql->bindValue( $key );
$sql->exec();
$result = $sql->getResult();
```
