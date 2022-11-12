{assign var='threadPushJCoins' value=$__wcf->getPushThreadJCoinsHandler()->getJCoins()}

{if $threadPushJCoins}
    <br>
    {if $__wcf->getPushThreadJCoinsHandler()->checkUserJCoins()}
        <p>{lang}wbb.thread.threadPush.jcoins{/lang}</p>
    {else}
        {lang}wbb.thread.threadPush.jcoins.notEnough{/lang}
    {/if}
{/if}
