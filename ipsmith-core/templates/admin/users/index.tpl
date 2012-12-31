<h2>Benutzer <small>Übersicht</small></h2>

<table class="table table-striped">
{foreach $entries as $entry  name='usersForeach'}

    {if ($smarty.foreach.usersForeach.iteration is div by
        $smarty.session.userdata.config.repeatheaders) ||
        ($smarty.foreach.usersForeach.first)}

    {if $smarty.foreach.usersForeach.first}
        <thead>
    {/if}

        <tr>
            {if $smarty.session.userdata.config.displayids eq "1"}
                <th>#</th>
            {/if}
            <th>Benutzername</th>
            <th>E-Mail</th>
            <th>Rollen</th>
            <th>&nbsp;</th>
        </tr>

    {if $smarty.foreach.usersForeach.first}
        </thead>
    {/if}

    {/if}
    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}
            <td>
                <strong>
                    {$entry.id}
                </strong>
            </td>
        {/if}
        <td>
            {if (isset($entry.firstname) && isset($entry.lastname)) &&
                ($entry.firstname neq "" && $entry.lastname neq "")}
            
                {$entry.firstname} {$entry.lastname}
                <br />
                <small>
                    ({$entry.username})
                </small>
            {else}
                {$entry.username}
            {/if}
        </td>
        <td>{if $entry.email neq ""}<a href="mailto:{$entry.email}">{$entry.email}</a>{else}-{/if}
        <td>{$entry.roles}</td>
        <td>{include file="admin/users/_usereditbutton.tpl" data=$entry}</td>
    </tr>
{/foreach}
</table>
