<?php

/**

 * @version		1.1.1

 * @package		Joomla

 * @subpackage	Helpdesk Pro

 * @author  Tuan Pham Ngoc

 * @copyright	Copyright (C) 2012 Ossolution Team

 * @license		GNU/GPL, see LICENSE.php

 */

// no direct access

defined( '_JEXEC' ) or die ;

$ordering = ($this->lists['order'] == 'a.ordering');

function limit_text($text, $limit=25) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
	  elseif (strlen($text)> 16*$limit) {
          $text =  mb_strcut($text, 0, 16*$limit).'...';
	  }
      return $text;
    }


JHTML::_('behavior.tooltip');	

?>

<div id="hdp_container" class="container-fluid">

<div class="qnacat">

<h1 class="hdp_title"><?php if ($this->category_id) echo $this->categoryTitle; else echo JText::_('HDP_MY_TICKETS'); ?>

<!--

<span class="newticket_link"><a href="<?php echo JRoute::_('index.php?option=com_helpdeskpro&task=ticket.add&Itemid='.JRequest::getInt('Itemid')); ?>"><?php echo JText::_('HDP_SUBMIT_TICKET'); ?></a></span>

-->

</h1>
<!--
<form action="<?php echo JRoute::_('index.php?option=com_helpdeskpro&view=tickets&Itemid='.JRequest::getInt('Itemid')); ?>" method="post" name="adminForm" id="adminForm">

<div class="row-fluid" id="hdp_filter_bar">

<table style="width:100%;">

	<tr>

		<td align="left" width="40%">

			<?php echo JText::_( 'Filter' ); ?>:

			<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="input-medium search-query" onchange="document.adminForm.submit();" />		

			<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'Go' ); ?></button>

			<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'Reset' ); ?></button>		

		</td>

		<td align="right">

			<?php echo $this->lists['category_id']; ?>

			<?php echo $this->lists['status_id']; ?>			

		</td>	

	</tr>

	</table>

</div>
-->

<?php if ($this->category_id) { ?>
<div id="hpd_ticket_list" class="row-fluid">


		<?php

		$k = 0;

		for ($i=0, $n=count( $this->items ); $i < $n; $i++)

		{

			$row = &$this->items[$i];

			$link 	= JRoute::_( 'index.php?option=com_helpdeskpro&view=ticket&id='. $row->id);													

			?>

			<div class="<?php echo "question$k"; ?>">	
																
				<h3><a href="<?php echo $link; ?>"><?php echo $row->subject ; ?></a> <?php if($row->status_id == 1) echo $this->statusList[$row->status_id]; ?></h3>

				<span class="small">#<?php echo $row->id; ?>, <?php echo JHtml::_('date', $row->created_date, $this->dateFormat); ?> |	<strong><?php echo $row->name ; ?></strong> </span><br />

				<?php echo limit_text($row->message) ; ?>					


			</div>	

			<?php

			$k = 1 - $k;

		}

		?>

	<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>

</div>	

<?php } else echo $this->home_page_text; ?>	

	<input type="hidden" name="option" value="com_helpdeskpro" />

	<input type="hidden" name="task" value="" />

	<input type="hidden" name="boxchecked" value="0" />

	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />

	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	

	<?php echo JHTML::_( 'form.token' ); ?>			

</form>

</div>

</div>

	<div class="sidebar-form">
	
		<?php include JPATH_ROOT.'/components/com_helpdeskpro/views/ticket/tmpl/form.php'; ?>
	
	</div>

</div>

<div id="aside" style="float: left; display: block; margin: 0 !important;" class="span3">

	<div class="sidebar-nav">

		<div class="moduletable">
	
			<?php echo $this->lists['cat']; ?>

		</div>

	</div>