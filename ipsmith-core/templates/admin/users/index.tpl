<h2>Benutzer <small>Übersicht</small></h2>

<table class="table table-striped sorttable">
    <thead>
        <tr>
            {if $smarty.session.userdata.config.displayids eq "1"}
                <th>#</th>
            {/if}
            <th>Benutzername</th>
            <th>E-Mail</th>
            <th>Rollen</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
<tbody>
{foreach $entries as $entry}
    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}
            <td>
                <strong>
                    {$entry.id}
                </strong>
            </td>
        {/if}
        <td>
            {$entry.username}
            {if (isset($entry.firstname) && isset($entry.lastname)) &&
                ($entry.firstname neq "" && $entry.lastname neq "")}
                <br />
                <small>
                    ({$entry.firstname} {$entry.lastname})
                </small>
            {/if}
        </td>
        <td>{if $entry.email neq ""}<a href="mailto:{$entry.email}">{$entry.email}</a>{else}-{/if}
        <td>{$entry.roles}</td>
        <td>{include file="admin/users/_usereditbutton.tpl" data=$entry}</td>
    </tr>
{/foreach}
</tbody>
</table>
