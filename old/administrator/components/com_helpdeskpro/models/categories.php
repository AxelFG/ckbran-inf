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

OSFactory::loadLibrary('modellist');
/**
 * Helpdesk Pro Component Categories Model
 *
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproModelCategories extends OSModelList
{	
	function __construct($config)
	{		
		$config['state_vars'] = array(				
				'parent_id' => array(0, 'int', 1)
		) ;
					
		parent::__construct($config);		
	}
	/**
	 * Method to get categories data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$parent = JRequest::getInt('parent_id', 0) ;
			$query = $this->_buildQuery();
			//We will build the data here
			$this->_db->setQuery($query);						
			$rows = $this->_db->loadObjectList();										
			$children = array();
			// first pass - collect children
			if (count($rows)) {
				foreach ($rows as $v )
				{
					$pt = $v->parent_id;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push( $list, $v );
					$children[$pt] = $list;
				}	
			}								
			$list = JHTML::_('menu.treerecurse', $parent, '', array(), $children, 9999);
			$total = count( $list );
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $total, $this->getState('limitstart'), $this->getState('limit') );	
			// slice out elements based on limits
			$list = array_slice( $list, $this->_pagination->limitstart, $this->_pagination->limit );
			$this->_data = $list ;												
		}
		return $this->_data;
	}		

	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
		$query = 'SELECT a.*, COUNT(b.id) AS total_tickets FROM #__helpdeskpro_categories AS a '
		.' LEFT JOIN #__helpdeskpro_tickets AS b '
		.' ON a.id = b.category_id '
		. $where
		.' GROUP BY a.id '
		. $orderby
		;
	
		return $query;
	}
	
	function _buildContentWhereArray() {
		$state = $this->getState() ;
		$where = parent::_buildContentWhereArray() ;
		if ($state->parent_id >0)
			$where[] = ' a.parent_id='.$state->parent_id ;
					
		return $where ;
	}
}