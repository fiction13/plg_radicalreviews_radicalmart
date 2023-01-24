<?php
/*
 * @package   plg_radicalreviews_radicalmart
 * @version   1.0.1
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalReviews\RadicalMart\Helper;

defined('_JEXEC') or die;

use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;

/**
 * @package     Helper class
 *
 * @since       0.0.0
 */
class RadicalMartHelper
{
	/**
	 * @var Registry
	 *
	 * @since 0.0.0
	 */
	protected $params;

	/**
	 * @var Input
	 *
	 * @since 0.0.0
	 */
	protected $input;

	/**
	 * @var array
	 *
	 * @since 0.0.0
	 */
	protected static $_contexts = [
		'com_radicalmart.product' => 'Product',
		'com_radicalmart.meta'    => 'Meta'
	];


	/**
	 * @param   Registry  $params
	 *
	 * @since 0.0.0
	 */
	public function __construct(Registry $params)
	{
		$this->params = $params;
		$this->input  = Factory::getApplication()->input;
	}

	/**
	 * Get items
	 *
	 * @param $item_id  int    Item id.
	 * @param $context  string Context.
	 *
	 * @since 0.0.0
	 */
	public static function getObject($item_id = null, $context = 'com_radicalmart.product')
	{
		$item = '';

		// Check context
		if (!isset(self::$_contexts[$context]))
		{
			return $item;
		}

		// Get app
		$app = Factory::getApplication();

		// Get model
		$model = $app->bootComponent('com_radicalmart')->getMVCFactory()->createModel(self::$_contexts[$context], 'Site', ['ignore_request' => true]);

		// Set application parameters in model
		$model->setState('params', new Registry());

		try
		{
			$item = $model->getItem($item_id);
		}
		catch (\Exception $e)
		{
			// Noop
		}

		return $item;
	}
}