<?php
//
// choose backend postgresql or sqlite (simple remove and/or add #)
//

$backend = "postgresql";    // php-version with PostgreSQL Support (pgsql) required
#$backend = "sqlite";   // php-version with PDO Driver for SQLite 3.x required

//
//  timezone
//  Difference to Greenwich time (GMT) in hours
//  Example: +0100 = MET
//  summertime will be choosen automaticly by the server.
//

$timezone = "+0100";

//
// postgresql data; (just examples, please edit)
// needed if backend postgresql
//

 $host = "localhost";
 $port = "5432";
 $user = "quassel";
 $password = "somepassword";
 $dbname = "quassel";
 
 
//
// path to quassel-storage.sqlite; (just examples, please edit)
// needed if backend sqlite
//
// Info: must be browsable with php (within the web-path) and readable! (tipp: links should also work)
//

$sqlitedb = "/path/to/quassel-storage.sqlite";

?>