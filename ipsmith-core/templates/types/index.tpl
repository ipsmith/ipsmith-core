<h2>Typen-&Uuml;bersicht</h2>

<table class="table table-striped">
{foreach $types as $type  name='typesForeach'}

    {if ($smarty.foreach.typesForeach.iteration is div by
        $smarty.session.userdata.config.repeatheaders) ||
        ($smarty.foreach.typesForeach.first)}

    {if $smarty.foreach.typesForeach.first}
        <thead>
    {/if}

    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}<th>#</th>{/if}
        <th>Typenname</th>
        <th class="span1">Hintergrundfarbe</th>
        <th class="span1">Vordergrundfarbe</th>
        <th class="span2">Darstellung</th>
        <th class="span1">Bearbeiten</th>
    </tr>

    {if $smarty.foreach.typesForeach.first}
        </thead>
    {/if}

    {/if}

    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}<th>{$type.id}</th>{/if}
        <td>{$type.typename}</td>
        <td>{$type.bgcolor_hex}</td>
        <td>{$type.fgcolor_hex}</td>
        <td>
         <span class="label" {if $type.bgcolor_hex neq ""}style="background-color:{$type.bgcolor_hex};color:{$type.fgcolor_hex}"{/if}>
            {$type.typename}
             </span>
        </td>
        <td>{include file="$currentModule/_editbutton.tpl" myentry=$type}</td>
    </tr>
{/foreach}
</table>
