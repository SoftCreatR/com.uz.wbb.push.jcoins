<?php
namespace wbb\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\WCF;

/**
 * Listener to check push thread JCoins.
 *
 * @author		2020-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wbb.push.jcoins
 */
class PushThreadJCoinsCheckListener implements IParameterizedEventListener {
	/**
	 * @inheritdoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!MODULE_JCOINS || JCOINS_ALLOW_NEGATIVE) {
			$parameters['canPush'] = true;
			return;
		}
		
		// must be user and have use JCoins
		if (!WCF::getUser()->userID || !WCF::getSession()->getPermission('user.jcoins.canEarn') || !WCF::getSession()->getPermission('user.jcoins.canUse')) {
			$parameters['canPush'] = true;
			return;
		}
		
		$statement = UserJCoinsStatementHandler::getInstance()->getStatementProcessorInstance('com.uz.wbb.push');
		$amount = $statement->calculateAmount();
		
		if ($amount >= 0) {
			$parameters['canPush'] = true;
			return;
		}
		
		if (WCF::getUser()->jCoinsAmount < -1 * $amount) {
			$parameters['canPush'] = false;
			return;
		}
	}
}
