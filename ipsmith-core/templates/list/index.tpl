<ul class="nav nav-tabs">
{foreach $currentcategories as $cat}
    <li {if $catid eq $cat.id} class="active"{/if}><a href="{$config.baseurl}/list/index.html?catid={$cat.id}&amp;locationid={$locationid}">{$cat.catname}</a></li>
{/foreach}
</ul>

<table class="table table-striped">
<thead>
    <tr>
{if $smarty.session.userdata.config.displayids eq "1"}<th>#</th>{/if}
        <th class="span1">Status</th>
        <th>IP</th>
        <th>&nbsp;</th>
        <th>Hostname</th>
        <th>Bemerkung</th>
        <th>Client-Info</th>
        <th class="span1">Bearbeiten</th>
    </tr>
</thead>
<tbody>
{foreach $entries as $entry}
    <tr {if isset($entry.color_hex) && $entry.color_hex neq ""}style="color:{$entry.color_hex};"{/if}>
        {if $smarty.session.userdata.config.displayids eq "1"}<td>{$entry.id}</td>{/if}
        <td>{include file="list/_pingresult.tpl" myentry=$entry}</td>
        <td>{$entry.ip}</td>
        <td>{if $entry.iconpath neq ""}<img src="{$entry.iconpath}" alt="" title="" />{else}&nbsp;{/if}</td>
        <td>{$entry.hostname}</td>
        <td>{$entry.note}</td>
        <td>{include file="list/_typeinfo.tpl" myentry=$entry}</td>
        <td>{include file="list/_editbutton.tpl" myentry=$entry}</td>
{/foreach}
</tbody>
</table>
