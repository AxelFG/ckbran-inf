<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
 defined('_JEXEC') or die('Restricted access'); 

$document = JFactory::getDocument();
$document->addStyleDeclaration('
.catselect{
	width:'.$params->get('select_width').'px;
}
');

$levels = explode("\r\n", $params->get('levels'));
ArrayHelper::clean_r($levels);
$max_level = count($levels);
?>

<script type="text/javascript">
	var max_level = <?php echo $max_level; ?>;

	!(function($){

		}).jQuery;
			
	function redirectToCategory(cat_id)
	{
		window.location = '<?php echo JURI::base(); ?>index.php?option=com_cobalt&view=records&section_id=<?php echo $section->id; ?>&cat_id='+cat_id;
	}

	function modJsc_getChilds_select( id, selectid, i)
	{
		if(!id)
		{
			return;
		}
		$.ajax({
			url: '<?php echo JURI::root(TRUE);?>/index.php?option=com_cobalt&task=ajax.category_childs&tmpl=component',
			dataType: 'json',
			type: 'POST',
			data:{cat_id: id}
		}).done(function(json) {
			if(!json)
			{
				return;
			}
			if(!json.success)
			{
				alert(json.error);
				return;
			}

			select = $("#"+ selectid);
			select.empty();
			if(json.result.length)
			{
				
				var opt = $(document.createElement("option")).attr({
					 value: ''
				});
				opt.text('<?php echo JText::_('- CSELECT -');?>');
				opt.appendTo(select);
				$.each(json.result, function(index, item){
					console.log(item)
					item.id = item.id.toInt();
					if( <?php echo $params->get('cat_empty', 1);?> == 1 || (<?php echo $params->get('cat_empty', 1);?> == 0 && (item.num_current > 0 || item.num_all > 0 )))
					{
						if(<?php echo ($params->get('cat_nums', 0) ? 1 : 0);?> != 0)
						{
							item.title += ' (' +( ('<?php echo $params->get('cat_nums');?>' == 'current') ? item.num_current : item.num_all) + ')';
						}

						var opt = $(document.createElement("option")).attr({
							 value: item.id						
						});
						opt.text(item.title);
						opt.appendTo(select);

					}
				});
			}
			else
			{
				var opt = $(document.createElement("option")).attr({
					 value: ''
				});
				opt.text('<?php echo JText::_('- CEMPTY -');?>');
				opt.appendTo(select);
				redirectToCategory(id);
			}
		});
	}
</script>

<div class="js_cc<?php echo $params->get('moduleclass_sfx') ?>">
	
<?php if ( $headerText ) : ?>
	<div class="js_cc"><?php echo $headerText ?></div>
<?php endif; ?> 

<?php if( $params->get( 'show_section', 1 ) ) : ?>
		<div <?php echo $params->get('section_class') ? 'class="'.$params->get('section_class').'"' : '';?>>   
			<?php if($params->get( 'show_section', 1 ) == 2) : ?>
				<a href="<?php echo JRoute::_($section->link);?>"><?php echo $section->name;?></a>
			<?php else :
				echo $section->name;				        
			endif;?>
		</div>
<?php endif; ?>


<?php if (count($categories)):?>
<div class="contentpane">
	<?php 			
	$i = 1;
	
	foreach ($levels as $level):
	
		if ($i == $max_level)
		{
			$function = 'redirectToCategory(this.value);';	
		}
		else
		{
			$function = 'modJsc_getChilds_select(this.value, \'category_select'.($i+1).'\', '.$i.');';
		}
	
		$options = array();
		$options[] = JHtml::_('select.option', '', '- Select -');
		if ($i == 1):
			
			foreach ($categories as $cat) 
			{
				if(!$params->get('cat_empty', 1) && !$cat->records_num) continue;
				if($params->get('cat_nums', 0))
				{
	 				$cat->title .= ' ('.($params->get('cat_nums', 'current') == 'current' ? modCobaltCategoriesHelper::getRecordsNum($section, $cat->id) : $cat->records_num).')';				
				}
				$options[] = JHtml::_('select.option', $cat->id, $cat->title);
			}
	?>
	<div>
		<?php echo JText::_($level);?><br />
		<?php echo JHtml::_('select.genericlist', $options, 'category_select1', 'class="catselect" onchange="'.$function.'"');?>
	</div>
	
	<?php else:?>
		<div>
			<?php echo JText::_($level);?><br />
			<?php echo JHtml::_('select.genericlist', $options, 'category_select'.$i, ' class="catselect" onchange="'.$function.'"');?>
		</div>
	<?php endif;?>
	<?php $i++;?>
<?php endforeach;?>
	
</div>
<?php endif;?>
	
	
<?php if ( $footerText ) : ?>
	<div class="js_cc<?php echo $params->get( 'moduleclass_sfx' ) ?>"><?php echo $footerText; ?></div>
<?php endif; ?>
	
</div>


<div class="clearfix"> </div>