<?php

        defined('_JEXEC') or die('Restricted Access');
        require_once( JApplicationHelper::getPath( 'toolbar_html' ) );
        switch($task)
        {
            case 'edit':
            case 'add':
                TOOLBAR_reviews::_NEW();
                break;
            default:
                TOOLBAR_reviews::_DEFAULT();
                break;
        }
?>
