<?php
/*
 * @package   plg_radicalreviews_radicalmart
 * @version   1.0.1
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

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
			<?php echo HTMLHelper::image($item->image, $item->title); ?>
        </div>
	<?php endif; ?>

	<?php if (!empty($item->link)) : ?>
        <div class=" uk-margin radicalreviews-radicalmart__image">
            <a class="uk-button uk-button-small uk-button-secondary uk-button-small uk-width-1-1"
               href="<?php echo Route::_($item->link); ?>">
				<?php echo Text::_('PLG_RADICALREVIEWS_RADICALMART_GO_BACK'); ?>
            </a>
        </div>
	<?php endif; ?>
</div>
