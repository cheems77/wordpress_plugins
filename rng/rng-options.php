<?php
echo "This tool will generate a random number between 1-100";
echo "<br /><br /><div>The random number generated on " . date('l d F Y') . " at " . date('H:i:s') . " is: </div>";
echo "<div style='font-size: 600%; clear: left; line-height: 150%;'>" . rand(0, 100) . "</div";
?>

<br clear="both" />
<form action=post>
<a href="tools.php?page=rng-options" title="Re-generate random number">Get another random number</a>
</form>