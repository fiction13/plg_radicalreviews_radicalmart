<?php
/*
 * @package   RadicalReviews - RadicalMart
 * @version   __DEPLOY_VERSION__
 * @author    Delo Design
 * @copyright Copyright (c) 2023 Delo Design. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://delo-design.ru
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\RadicalMart\Site\Helper\MediaHelper;

extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  object $item Object data.
 *
 */

Factory::getApplication()->getLanguage()->load('plg_radicalreviews_radicalmart', JPATH_ADMINISTRATOR);

?>

<div class="radicalreviews-radicalmart">
	<?php if (!empty($item->image)) : ?>
        <div class="radicalreviews-radicalmart__image">
            <a class="d-block" href="<?php echo Route::_($item->link); ?>">
				<?php echo MediaHelper::renderImage(
					'com_radicalmart.product.review.form',
					$item->image,
					[
						'alt'     => $item->title,
						'loading' => 'lazy',
						'class'   => 'mh-100 mw-100 rounded'
					],
					[
						'product_id' => $item->id,
						'no_image'   => true,
						'thumb'      => true,
					]); ?>
            </a>
        </div>
	<?php endif; ?>

	<?php if (!empty($item->link)) : ?>
        <div class="mt-3 radicalreviews-radicalmart__button">
            <a class="btn btn-small btn-primary w-100"
               href="<?php echo Route::_($item->link); ?>">
				<?php echo Text::_('PLG_RADICALREVIEWS_RADICALMART_GO_BACK'); ?>
            </a>
        </div>
	<?php endif; ?>
</div>
