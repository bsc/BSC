<?php
//defined( '_VALID_MOS' ) or die( 'Restricted access' );
if (!(defined( '_VALID_MOS' ) || defined( '_JEXEC' ))) die( 'Restricted access3' );


if (defined('_VALID_MOS')) {
switch ($task) {
        case "new" :
                mosMenuBar::startTable();
                mosMenuBar::save('create');
                mosMenuBar::spacer();
                mosMenuBar::cancel( 'cancel' );
                mosMenuBar::spacer();
                mosMenuBar::endTable();
                break;
        case "edit" :
                mosMenuBar::startTable();
                mosMenuBar::save();
                mosMenuBar::spacer();
                mosMenuBar::cancel( 'cancel' );
                mosMenuBar::spacer();
                mosMenuBar::endTable();
                break;
        case "execsql" :
        default:
                mosMenuBar::startTable();
                mosMenuBar::spacer();
                mosMenuBar::save('tocsv',_ES_TOCSV);
                mosMenuBar::spacer();
                mosMenuBar::addnew();
                mosMenuBar::spacer();
                mosMenuBar::apply('execsql', _ES_EXECSQL);
                mosMenuBar::spacer();
                mosMenuBar::endTable();
                break;
}
} else {
switch ($task) {
        case "new" :
                JToolBarHelper::save('create');
                JToolBarHelper::divider();
                JToolBarHelper::cancel( 'cancel' );
                JToolBarHelper::divider();
                JToolBarHelper::endTable();
                break;
        case "edit" :
                JToolBarHelper::save();
                JToolBarHelper::divider();
                JToolBarHelper::cancel( 'cancel' );
                break;
        case "execsql" :
        default:
                JToolBarHelper::divider();
                JToolBarHelper::save('tocsv',_ES_TOCSV);
                JToolBarHelper::divider();
                JToolBarHelper::addnew();
                JToolBarHelper::divider();
                JToolBarHelper::apply('execsql', _ES_EXECSQL);
                JToolBarHelper::divider();
                break;
}
}

?>