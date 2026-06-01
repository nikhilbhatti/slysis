<?php
// Hostinger par GitHub se automatic code pull karne ke liye script
echo "<h2>GitHub se live code khinch raha hu...</h2>";

// Git pull command chalane ke liye
$output = shell_exec('git pull origin main 2>&1');

echo "<pre>$output</pre>";
echo "<h3>Deployment Done!</h3>";
?>