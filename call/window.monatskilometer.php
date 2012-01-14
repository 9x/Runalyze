<?php
/**
 * File for displaying the kilometers of each month.
 * Call:   call/window.monatskilometer.php
 */
require '../inc/class.Frontend.php';
$Frontend = new Frontend(true, __FILE__);
$Frontend->displayHeader();

if (!isset($_GET['y']))
	$_GET['y'] = date("Y");
?>

<h1>Monatskilometer</h1>

<div style="position:relative;width:802px;height:502px;margin:2px auto;">
	<div class="flot waitImg" id="monthKM<?php echo $_GET['y']; ?>" style="width:800px;height:500px;position:absolute;"></div>
</div>

<?php
include FRONTEND_PATH.'draw/Plot.MonthKM.php';
?>

	<br />
	<br />

<center>
<?php
for ($j = START_YEAR; $j <= date("Y"); $j++) {
	if ($j == $_GET['y'])
		echo '<strong style="margin-right:20px;">'.$j.'</strong>';
	else
		echo Ajax::window('<a href="call/window.monatskilometer.php?y='.$j.'" style="margin-right:20px;">'.$j.'</a>');	
}
?>
</center>

<?php
$Frontend->displayFooter();
$Frontend->close();
?>