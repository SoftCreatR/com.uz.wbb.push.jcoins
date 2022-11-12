<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: https://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace wbb\system\event\listener;

use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\WCF;

/**
 * Listener for actions before push.
 */
class PushThreadJCoinsBeforeListener implements IParameterizedEventListener
{
    /**
     * @inheritdoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
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
