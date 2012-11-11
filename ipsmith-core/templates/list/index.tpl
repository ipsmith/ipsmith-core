<ul class="nav nav-tabs">
{foreach $currentcategories as $cat}
    <li {if $catid eq $cat.id} class="active"{/if}><a href="{$config.baseurl}/list/index.html?catid={$cat.id}&locationid={$locationid}">{$cat.catname}</a></li>
{/foreach}
</ul>

<table class="table table-striped">
<thead>
    <tr>
{if $smarty.session.userdata.config.displayids eq "1"}        <th>#</th>{/if}
        <th class="span1">Status</th>
        <th>IP</th>
        <th>Hostname</th>
        <th>Bemerkung</th>
        <th>OS</th>
        <th>Client-Info</th>
        <th>Bearbeiten</th>
    </tr>
</thead>
<tbody>
{foreach $entries as $entry}
    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}<td>{$entry.id}</td>{/if}
        <td>{include file="list/_pingresult.tpl" myentry=$entry}</td>
        <td>{$entry.ip}</td>
        <td>{$entry.hostname}</td>
        <td>{$entry.note}</td>
        <td>-</td>
        <td>-</td>
        <td>{include file="list/_editbutton.tpl" myentry=$entry}</td>
{/foreach}
</tbody>
</table>
