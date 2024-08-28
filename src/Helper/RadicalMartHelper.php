<?php
/*
 * @package   RadicalReviews - RadicalMart
 * @version   __DEPLOY_VERSION__
 * @author    Delo Design
 * @copyright Copyright (c) 2023 Delo Design. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://delo-design.ru
 */

namespace Joomla\Plugin\RadicalReviews\RadicalMart\Helper;

defined('_JEXEC') or die;

use Joomla\Database\ParameterType;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * @package     Helper class
 *
 * @since       1.0.1
 */
class RadicalMartHelper
{
	/**
	 * @var Registry
	 *
	 * @since 1.0.1
	 */
	protected $params;

	/**
	 * @var Input
	 *
	 * @since 1.0.1
	 */
	protected $input;

	/**
	 * @var array
	 *
	 * @since 1.0.1
	 */
	protected static $_contexts = [
		'com_radicalmart.product' => 'Product',
		'com_radicalmart.meta'    => 'Meta'
	];


	/**
	 * @param   Registry  $params
	 *
	 * @since 1.0.1
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
	 * @since 1.0.1
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

	/**
	 * Get product group (meta)
	 *
	 * @param $pk  int    Item id.
	 *
	 * @since 1.0.1
	 */
	public static function getGroup($pk = null)
	{
		// Get meta product
		$db = Factory::getContainer()->get('DatabaseDriver');

		$query = $db->getQuery(true)
			->select(['m.id', 'm.products'])
			->from($db->quoteName('#__radicalmart_metas', 'm'))
			->where($db->quoteName('type') . ' = ' . $db->quote('variability'))
			->where('JSON_VALUE(m.products, ' . $db->quote('$.p' . $pk . '.id') . ') IS NOT NULL')
			->where($db->quoteName('m.state') . ' = 1')
			->whereIn('m.language', [Factory::getApplication()->getLanguage()->getTag(), '*'],
				ParameterType::STRING);

		if ($meta = $db->setQuery($query)->loadObject())
		{
			$products = (new Registry($meta->products))->toArray();

			return ArrayHelper::toInteger(ArrayHelper::getColumn($products, 'id'));
		}

		return array();
	}
}