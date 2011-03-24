<?php
/**
 * @version		$Id: block.php 544 2011-01-15 04:40:24Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

juimport('joomla.application.component.controllerform');

/**
 * Category Subcontroller.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserControllerBlock extends JControllerForm
{
	/**
	 * Apply the block request.
	 *
	 * @return	void
	 * @since	1.1
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken();

		// Initialise variables.
		$model		= $this->getModel();
		$userIds	= JRequest::getVar('user_ids');
		$data		= array(
			'block_catid'	=> JRequest::getInt('block_catid'),
			'note'			=> JRequest::getVar('note'),
			'review_time'	=> JRequest::getVar('review_time'),
		);

		try
		{
			// Validate the posted data.
			// Sometimes the form needs some posted data, such as for plugins and modules.
			$form = $model->getForm($data, false);

			if (!$form) {
				$app->enqueueMessage($model->getError(), 'error');

				return false;
			}

			// Test if the data is valid.
			$validData = $model->validate($data);

			// Check for validation errors.
			if ($validData === false) {
				throw new Exception($model->getError());
			}

			$blockCatId = $validData['block_catid'];
			$note		= $validData['note'];
			$reviewTime	= $validData['review_time'];

			if ($blockCatId) {
				$model->setCategory($blockCatId, $userIds);
			}

			if ($note && trim(strip_tags($note))) {
				$model->setNote($note, $reviewTime, $userIds);
			}

			JArrayHelper::toInteger($userIds);

			$model->setBlock($userIds);

			$this->setRedirect(
				JRoute::_('index.php?option=com_artofuser&view=users', false),
				JText::_('COM_ARTOFUSER_USERS_BLOCKED')
			);
		}
		catch (Exception $e)
		{
			$this->setRedirect(
				JRoute::_('index.php?option=com_artofuser&view=users', false),
				$e->getMessage(),
				'warning'
			);
		}
	}

	/**
	 * Cancel the block request.
	 *
	 * @return	void
	 * @since	1.1
	 */
	public function cancel()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=users', false));
	}

	/**
	 * Display the view.
	 *
	 * @return	void
	 * @since	1.1
	 */
	public function display()
	{
		// Check for request forgeries.
		JRequest::checkToken();

		JRequest::setVar('view', 'block');

		return JController::display();
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 * @param	array	$config	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 * @since	1.6
	 */
	public function getModel($name = '', $prefix = '', $config = array())
	{
		if (empty($name)) {
			$name = $this->context;
		}

		return parent::getModel($name, $prefix, $config);
	}

}