<?php
// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.

switch ($item->browserNav) :
	default:
	case 0:
?><a href="<?php echo $item->flink; ?>"><?php echo $item->title; ?></a><?php
		break;
	case 1:
		// _blank
?><a href="<?php echo $item->flink; ?>" target="_blank"><?php echo $item->title; ?></a><?php
		break;
	case 2:
		// window.open
		$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$item->params->get('window_open');
?><a href="<?php echo $item->flink.'&tmpl=component'; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $attribs;?>');return false;"><?php echo $item->title; ?></a><?php
		break;
endswitch;
