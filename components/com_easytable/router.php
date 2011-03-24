<?php
	defined('_JEXEC') or die ('Restricted Access');
	function EasyTableBuildRoute(&$query)
	{
		$segments = array();
		if(isset($query['view']))
			{
			$segments[] = $query['view'];
			unset($query['view']);
			}
		if(isset($query['id']))
			{
			$segments[] = $query['id'];
			unset($query['id']);
			}
        if(isset($query['rid']))
            {
            $segments[] = $query['rid'];
            unset($query['rid']);
            }
		return $segments;
	}
	
	function EasyTableParseRoute ($segments) {
		$vars = array();
		if (isset($segments[0])) {
			$vars['view'] = $segments[0];
		}
		if (isset($segments[1])) {
			$vars['id'] = $segments[1];
		}
        if (isset($segments[2])) {
            $vars['rid'] = $segments[2];
        }
    return $vars;
	}
?>
