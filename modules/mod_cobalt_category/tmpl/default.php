<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
if(!function_exists('mod_getChildsDef')) { function mod_getChildsDef($category, $params, $parents, $k = 1) { ?>
	<ul>
		<?php foreach($category->children as $i => $cat ) :
			$class = array();
			$class[] = 'item-'.$cat->id;
			if(in_array($cat->id, $parents))
				$class[] = 'active';
			if($cat->childs_num)
				$class[] = 'parent';
			?>
			<li class="<?php echo implode(' ', $class);?>">

				<a href="<?php echo JRoute::_($cat->link)?>">
					<?php echo $cat->title;?>
					<?php if($params->get('cat_nums', 0)):?>
						<span class="small">(<?php echo $cat->records_num; ?>)</span>
					<?php endif;?>
				</a>
				<?php if(in_array($cat->id, $parents) && $cat->childs_num):?>
					<?php mod_getChildsDef($cat, $params, $parents, $k + 1);?>
				<?php endif;?>
			</li>
		<?php endforeach;?>
	</ul>
<?php }}?>
<div>
	
	<?php if ( $headerText ) : ?>
		<div class="js_cc"><?php echo $headerText; ?></div>
	<?php endif; ?> 
	
	<?php if( $params->get( 'show_section', 1 ) ) : ?>
			<div <?php echo $params->get('section_class') ? 'class="'.$params->get('section_class').'"' : '';?>>   
				<?php if($params->get( 'show_section', 1 ) == 2) : ?>
					<a href="<?php echo JRoute::_($section->link);?>"><?php echo $section->title ? $section->title : $section->name;?></a>
				<?php else :
                        echo $section->title ? $section->title : $section->name;				        
				    endif;?>
			</div>
	<?php endif; ?>
		
	<ul class="menu">
		<?php foreach ($categories as $cat) :
			if (!$params->get('cat_empty', 1) && !$cat->records_num) continue;
			$class = array();  
			$class[] = 'item-'.$cat->id;
			if(in_array($cat->id, $parents))
				$class[] = 'active';
			if($cat->childs_num)
				$class[] = 'parent';
		
		?>
		<li class="<?php echo implode(' ', $class);?>">				
			<a href="<?php echo JRoute::_($cat->link)?>">
				<?php echo $cat->title;?>
				<?php if($params->get('cat_nums', 0)):?>
					<span class="small">(<?php echo $cat->records_num; ?>)</span>
				<?php endif;?>				
			</a>			
			<?php if(in_array($cat->id, $parents) && $cat->childs_num):?>
				<?php mod_getChildsDef($cat, $params, $parents);?>
			<?php endif;?>
		</li>
		<?php endforeach;?>
		<?php if($params->get('records') && $section->records):
			foreach ($section->records as $i => $rec):
				if($params->get('records_limit') && $i == $params->get('records_limit') ):
					$rec->title = JText::_('CMORERECORDS');
					$rec->id = -1;
					$rec->url = $section->link;
				endif;
			?>
			<li class="<?php echo implode(' ', $class);?>">				
				<a href="<?php echo JRoute::_($rec->url)?>">
					<?php echo $rec->title;?>								
				</a>
			</li>
			<?php endforeach;?>
		<?php endif; ?>
	</ul>

	<?php if ( $footerText ) : ?>
		<div class="js_cc<?php echo $params->get( 'moduleclass_sfx' ) ?>"><?php echo $footerText; ?></div>
	<?php endif; ?>
	
</div>

<div class="clearfix"> </div>