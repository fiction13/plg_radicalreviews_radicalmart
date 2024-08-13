<?php
/*
 * @package   RadicalReviews - RadicalMart
 * @version   1.0.1
 * @author    Delo Design
 * @copyright Copyright (c) 2023 Delo Design. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://delo-design.ru
 */

namespace Joomla\Plugin\RadicalReviews\RadicalMart\Extension;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\RadicalReviews\Administrator\Traits\PluginTrait;
use Joomla\Component\RadicalReviews\Site\Helper\ReviewsHelper;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\Plugin\RadicalReviews\RadicalMart\Helper\RadicalMartHelper;
use Joomla\Registry\Registry;

class RadicalMart extends CMSPlugin implements SubscriberInterface
{
	use PluginTrait;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    bool
	 *
	 * @since  1.0.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Loads the application object.
	 *
	 * @var  \Joomla\CMS\Application\CMSApplication
	 *
	 * @since  1.0.1
	 */
	protected $app = null;

	/**
	 * Loads the database object.
	 *
	 * @var  \Joomla\Database\DatabaseDriver
	 *
	 * @since  1.0.1
	 */
	protected $db = null;

	/**
	 * RadicalMart component params.
	 *
	 * @var  Registry|null
	 *
	 * @since  1.0.1
	 */
	protected ?Registry $componentParams = null;

	/**
	 * @var string[]
	 *
	 * @since 1.0.1
	 */
	private const CONTEXTS_MAP = [
		'com_radicalmart.product' => [
			'prefix' => 'Joomla\Component\RadicalMart\Administrator\Field\Modal',
			'field'  => 'product'
		],
	];

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   1.0.1
	 */
	public static function getSubscribedEvents(): array
	{
		return [
//			'onRadicalReviewsAdminForm'  => 'onRadicalReviewsAdminForm',
			'onRadicalReviewsConfigForm'  => 'onRadicalReviewsConfigForm',
			'onRadicalReviewsGetObject'   => 'onRadicalReviewsGetObject',
			'onRadicalReviewsGetContexts' => 'onRadicalReviewsGetContexts',
			'onContentAfterTitle'         => 'onContentAfterTitle',
			'onContentBeforeDisplay'      => 'onContentBeforeDisplay',
			'onContentAfterDisplay'       => 'onContentAfterDisplay'
		];
	}

	/**
	 * Constructor.
	 *
	 * @param   DispatcherInterface  &$subject  The object to observe.
	 * @param   array                 $config   An optional associative array of configuration settings.
	 *
	 * @since  1.0.1
	 */
	public function __construct(&$subject, $config = [])
	{
		$this->componentParams = ComponentHelper::getParams('com_radicalreviews');

		parent::__construct($subject, $config);
	}

	/**
	 * Method to get review object.
	 *
	 * @param   int     $id       Item id
	 * @param   string  $context  Item context
	 *
	 * @throws  \Exception
	 *
	 * @since  1.0.0
	 */
	public function onRadicalReviewsGetObject(int $id, string $context)
	{
		if ($context !== 'com_radicalmart.product')
		{
			return false;
		}

		return RadicalMartHelper::getObject($id, $context);
	}

	/**
	 * The display event.
	 *
	 * @param   string     $context     The context
	 * @param   \stdClass  $item        The item
	 * @param   Registry   $params      The params
	 * @param   integer    $limitstart  The start
	 *
	 * @since   1.0.1
	 */
	public function onContentAfterTitle(Event $event)
	{
		$context = $event->getArgument(0);
		$item    = $event->getArgument(1);
		$params  = $event->getArgument(2);
		$result  = $event->getArgument('result', []);

		$result[] = $this->display($context, $item, $params, 1);

		$event->setArgument('result', $result);
	}

	/**
	 * The display event.
	 *
	 * @param   string     $context     The context
	 * @param   \stdClass  $item        The item
	 * @param   Registry   $params      The params
	 * @param   integer    $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	public function onContentBeforeDisplay(Event $event)
	{
		$context = $event->getArgument(0);
		$item    = $event->getArgument(1);
		$params  = $event->getArgument(2);
		$result  = $event->getArgument('result', []);

		$result[] = $this->display($context, $item, $params, 2);

		$event->setArgument('result', $result);
	}

	/**
	 * The display event.
	 *
	 * @param   string     $context     The context
	 * @param   \stdClass  $item        The item
	 * @param   Registry   $params      The params
	 * @param   integer    $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	public function onContentAfterDisplay(Event $event)
	{
		$context = $event->getArgument(0);
		$item    = $event->getArgument(1);
		$params  = $event->getArgument(2);
		$result  = $event->getArgument('result', []);

		$result[] = $this->display($context, $item, $params, 3);

		$event->setArgument('result', $result);
	}

	/**
	 * Performs the display event.
	 *
	 * @param   string     $context      The context
	 * @param   \stdClass  $item         The item
	 * @param   Registry   $params       The params
	 * @param   integer    $displayType  The type
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	private function display($context, $item, $params, $displayType)
	{
		if ($this->app->isClient('site')
			&& strpos($context, 'radicalmart') !== false
			&& $this->app->input->get('option') === 'com_radicalmart')
		{
			$result                   = array();
			$paramsDisplayTypeStats   = (int) $this->componentParams->get('radicalmart_display_type_stats');
			$paramsDisplayTypeReviews = (int) $this->componentParams->get('radicalmart_display_type_reviews');

			// Display stats
			if ($displayType === $paramsDisplayTypeStats)
			{
				$result[] = ReviewsHelper::renderStats($item->id, $context);
			}

			// Display reviews
			if ($displayType === $paramsDisplayTypeReviews)
			{
				$limit    = (int) $this->componentParams->get('radicalmart_display_reviews_limit', 10);
				$result[] = ReviewsHelper::renderReviews($item->id, $context, $limit);
			}

			return implode("\n", $result);
		}

		return '';
	}
}