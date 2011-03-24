<?php
defined('_JEXEC') or die('Restricted Access');
require_once(JApplicationHelper::getPath('admin_html'));
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

switch ($task)
{
    case 'edit':
    case 'add':
        editReview($option);
        break;
    case 'apply':
    case 'save':
        saveReview($option);
        break;
    case 'remove':
        removeReviews( $option );
        break;

    default:
        showReviews( $option );
        break;
        
}
function editReview($option)
{
    $row =& JTable::getInstance('review', 'Table');
    $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
    $id = $cid[0];

    $row->load($id);

    $lists = array();
    $reservations = array('0' => array('value' => 'None Taken',
                          'text' => 'None Taken'),
                            '1' => array('value' => 'Accepted',
                            'text' => 'Accepted'),
                            '2' => array('value' => 'Suggested',
                            'text' => 'Suggested'),
                            '3' => array('value' => 'Required',
                            'text' => 'Required'),
                     );
    $lists['reservations'] = JHTML::_('select.genericList',$reservations, 'reservations', 'class="inputbox" '. '', 'value',    'text', $row->reservations );
    $lists['smoking'] = JHTML::_('select.booleanlist', 'smoking','class="inputbox"', $row->smoking);
    $lists['published'] = JHTML::_('select.booleanlist', 'published','class="inputbox"', $row->published);
    HTML_reviews::editReview($row, $lists, $option);
}
function saveReview( $option, $task )
{
    global $mainframe;
    $row =& JTable::getInstance('Review', 'Table');

    if (!$row->bind(JRequest::get('post')))
    {
        echo "<script> alert('".$row->getError()."');
        window.history.go(-1); </script>\n";
        exit();
    }
    $row->quicktake = JRequest::getVar( 'quicktake', '', 'post','string', JREQUEST_ALLOWRAW );
    $row->review = JRequest::getVar( 'review', '', 'post','string', JREQUEST_ALLOWRAW );

    if(!$row->review_date)
        $row->review_date = date( 'Y-m-d H:i:s' );

    if (!$row->store())
    {
        echo "<script> alert('".$row->getError()."');
        window.history.go(-1); </script>\n";
        exit();
    }

    switch ($task)
    {
        case 'apply':
            $msg = 'Changes to Review saved';
            $link = 'index.php?option=' . $option .
            '&task=edit&cid[]='. $row->id;
            break;
        case 'save':
            default:
            $msg = 'Review Saved';
            $link = 'index.php?option=' . $option;
            break;
    }
    $mainframe->redirect($link, $msg);
}
function showReviews( $option )
{
    $db =& JFactory::getDBO();
    $query = "SELECT * FROM #__reviews";
    $db->setQuery( $query );
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    HTML_reviews::showReviews( $option, $rows );
}
function removeReviews( $option )
{
    global $mainframe;
    $cid = JRequest::getVar( 'cid', array(), '', 'array' );
    $db =& JFactory::getDBO();
    if(count($cid))
    {
        $cids = implode( ',', $cid );
        $query = "DELETE FROM #__reviews WHERE id IN ( $cids )";
        $db->setQuery( $query );
        if (!$db->query())
        {
            echo "<script> alert('".$db->getErrorMsg()."');
            window.history.go(-1); </script>\n";
        }
    }
    $mainframe->redirect( 'index.php?option=' . $option );
}


?>
