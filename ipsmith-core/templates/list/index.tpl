<ul class="nav nav-tabs">
    <li {if $catid eq 0} class="active"{/if}><a href="{$config.baseurl}/list/index.html?catid=0&amp;locationid={$locationid}">ALLE</a></li>
{foreach $currentcategories as $cat }
    <li {if $catid eq $cat->id} class="active"{/if}><a href="{$config.baseurl}/list/index.html?catid={$cat->id}&amp;locationid={$locationid}">{$cat->catname}</a></li>
{/foreach}
</ul>

<table class="table table-striped">
{foreach $entries as $entry  name='entriesForeach'}
    {if ($smarty.foreach.entriesForeach.iteration is div by
        $smarty.session.userdata.config.repeatheaders) ||
        ($smarty.foreach.entriesForeach.first)}

    {if $smarty.foreach.entriesForeach.first}
        <thead>
    {/if}

    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}
            <th>#</th>
        {/if}
        <th class="span1">Status</th>
        {if $smarty.session.userdata.config.displayids eq "1"}
            <th>IP-Typ</th>
        {/if}
        <th>IP</th>
        <th>Hostname</th>
        <th>MAC</th>
        <th>&nbsp;</th>
        <th>Client-Info</th>
        <th class="span1">Bearbeiten</th>
    </tr>

    {if $smarty.foreach.entriesForeach.first}
        </thead>
    {/if}

    {/if}


    <tr {if isset($entry->color_hex) && $entry->color_hex neq ""}style="color:{$entry->color_hex};"{/if}>
        {if $smarty.session.userdata.config.displayids eq "1"}
            <td>{$entry->id}</td>
        {/if}
        <td>{include file="list/_pingresult.tpl" myentry=$entry}</td>
        {if $smarty.session.userdata.config.displayids eq "1"}
            <td><span class="label {if $entry->address->IPType eq "IPv6"}label-success{/if}">{$entry->address->IPType}</span></td>
        {/if}
        <td>{$entry->address->IP}</td>
        <td>{$entry->hostname}</td>
        <td>{$entry->macaddress}</td>
        <td>{if $entry->iconpath neq ""}<img src="{$entry->iconpath}" alt="" title="" />{else}&nbsp;{/if}</td>
        <td>{include file="list/_typeinfo.tpl"}</td>
        <td>{include file="list/_editbutton.tpl"}</td>
{/foreach}
</table>
