<?php
	defined('_JEXEC') or die ('Restricted Access');
	
	class JElementEasyTable extends JElement 
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'EasyTable';
	
		function fetchElement($name, $value, &$node, $control_name)
		{
			$db =& JFactory::getDBO();
            $result ='';

            if($name = 'id')
            {
                $elementQuery = 'SELECT id, easytablename FROM #__easytables WHERE published = 1 ORDER BY easytablename';

                $db->setQuery($elementQuery);
                $options = $db->loadObjectList();
				$noneSelected = array();
				$noneSelected[] = array('id' => 0,'easytablename' => '-- '.JText::_( "None Selected" ).' --');
                array_splice($options,0,0,$noneSelected);
                
                $result = JHTML::_('select.genericlist',	$options,							// [array of value/label pairs, ie. the value of the labels shown in the list]
															$control_name. '[' . $name . ']',	// so that we end up with a params[id] style name for the html control
															'class="inputbox"',					// optionals attributes of the select control
															'id',								// the key in the array for the value of list items
															'easytablename',					// the key in the array for the label used in the select list
															$value,								// the current value, used to indicate the selected item in the list
															$control_name . $name				// used for the id of the control?
									);
            }

            return $result;
		}
	}
