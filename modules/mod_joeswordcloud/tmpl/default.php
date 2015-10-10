<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="joeswordcloud"<?php echo $joeswordcloud_params->moduleclasssfx.$joeswordcloud_params->modulewidth; ?>>
	<?php
 		if ($module->showtitle && 0)
		{
			echo "<h3 class=\"page-header\"><span>" . $module->title . "</span></h3>";
		}
		echo '<div>'.$joeswordcloud_params->modulecontent.'</div>';
	?>
</div>
<?php
	if ($joeswordcloud_params->showdebug) {
		echo $joeswordcloud_params->debug;
	}
?>