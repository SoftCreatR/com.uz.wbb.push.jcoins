{assign var='threadPushJCoins' value=$__wcf->getPushThreadJCoinsHandler()->getJCoins()}

{if $threadPushJCoins}
	{assign var='addLang' value='wbb.thread.threadPush.jcoins'}
{/if}