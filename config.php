<?php
//
// choose backend postgresql or sqlite
//

$backend = "postgresql";
#$backend = "sqlite";

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
// must be browsable with php ! (tipp for unix: "ln -s" is your friend)
//

$sqlitedb = "/path/to/quassel-storage.sqlite";

?>