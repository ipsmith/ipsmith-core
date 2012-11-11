<h2>Benutzer <small>Übersicht</small></h2>

<table class="table table-striped sorttable">
    <thead>
        <tr>
            <th>#</th>
            <th>Benutzername</th>
            <th>E-Mail</th>
            <th>Rollen</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
<tbody>
{foreach $entries as $entry}
    <tr>
        <td><strong>{$entry.id}</strong></td>
        <td>{$entry.username}</td>
        <td>{if $entry.email neq ""}<a href="mailto:{$entry.email}">{$entry.email}</a>{else}-{/if}
        <td>{$entry.roles}</td>
        <td>{include file="admin/users/_usereditbutton.tpl" data=$entry}</td>
    </tr>
{/foreach}
</tbody>
</table>
