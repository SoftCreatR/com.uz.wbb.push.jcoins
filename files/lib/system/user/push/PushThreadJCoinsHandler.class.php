<?php
namespace wbb\system\user\push;
use wcf\data\user\User;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles push thread JCoins.
 * 
 * @author		2020-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wbb.push.jcoins
 */
class PushThreadJCoinsHandler extends SingletonFactory {
	/**
	 * Returns JCoins amount for push.
	 */
	public function getJCoins() {
		$statement = UserJCoinsStatementHandler::getInstance()->getStatementProcessorInstance('com.uz.wbb.push');
		return -1 * $statement->calculateAmount();
	}
	
	/**
	 * Returns true, if user has enough JCoins for push.
	 */
	public function checkUserJCoins() {
		if (!MODULE_JCOINS || JCOINS_ALLOW_NEGATIVE) {
			return true;
		}
		
		// must be user and have permission
		if (!WCF::getUser()->userID || !WCF::getSession()->getPermission('user.jcoins.canEarn') || !WCF::getSession()->getPermission('user.jcoins.canUse')) {
			return true;
		}
		
		$statement = UserJCoinsStatementHandler::getInstance()->getStatementProcessorInstance('com.uz.wbb.push');
		$amount = $statement->calculateAmount();
		if ($amount >= 0) {
			return true;
		}
		if (WCF::getUser()->jCoinsAmount < -1 * $amount) {
			return false;
		}
		
		return true;
	}
}
