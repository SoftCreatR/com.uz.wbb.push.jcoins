<?php
namespace wbb\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\WCF;

/**
 * Listener for actions after push.
 *
 * @author		2020-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wbb.push.jcoins
 */
class PushThreadJCoinsAfterListener implements IParameterizedEventListener {
	/**
	 * @inheritdoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!MODULE_JCOINS || !MODULE_PUSH_THREAD) return;
		
		// assign JCoins unless canPushThreadUnlimited
		if (WCF::getSession()->getPermission('user.board.canPushThreadUnlimited')) return;
		
		UserJCoinsStatementHandler::getInstance()->create('com.uz.wbb.push', $parameters['thread']);
	}
}
