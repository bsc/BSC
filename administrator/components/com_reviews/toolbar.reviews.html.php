<?php

    defined('_JEXEC') or die('Restricted Access');
    #doc
    #    classname:    TOOLBAR_reviews
    #    scope:        PUBLIC
    #
    #/doc
    
    class TOOLBAR_reviews 
    {
        function _NEW() {
            JToolBarHelper::save();
            JToolBarHelper::apply();
            JToolBarHelper::cancel();
        }
        function _DEFAULT() {
            JToolBarHelper::title( JText::_( 'Restaurant Reviews' ), 'generic.png' );
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::editList();
            JToolBarHelper::deleteList();
            JToolBarHelper::addNew();
        }
    }
    ###
    


?>
