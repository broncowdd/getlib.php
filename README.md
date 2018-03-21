# getlib.php
get libs from distant servers to local (&amp; avoid unnecessary requests to servers that logs user's connections)
ex:  
 https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js
becomes
 getlib.php?url=https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js
if you want to update local file if the distant one changes, just add "update" 
 getlib.php?update&url=https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js



author: warriordudimanche.net
