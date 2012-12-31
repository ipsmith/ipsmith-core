{if isset($smarty.session.currentmessages)}
    {foreach $smarty.session.currentmessages as $key => $value}
            <div class="alert alert-{$key}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {foreach $value as $message}
                    {$message}<br />
                {/foreach}
            </div>
    {/foreach}
{/if}