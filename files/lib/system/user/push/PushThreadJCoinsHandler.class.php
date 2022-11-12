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

namespace wbb\system\user\push;

use wcf\data\user\User;
use wcf\system\SingletonFactory;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\WCF;

/**
 * Handles push thread JCoins.
 */
class PushThreadJCoinsHandler extends SingletonFactory
{
    /**
     * Returns JCoins amount for push.
     */
    public function getJCoins()
    {
        $statement = UserJCoinsStatementHandler::getInstance()->getStatementProcessorInstance('com.uz.wbb.push');

        return -1 * $statement->calculateAmount();
    }

    /**
     * Returns true, if user has enough JCoins for push.
     */
    public function checkUserJCoins()
    {
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
