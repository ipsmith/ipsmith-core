<h2>Typen-&Uuml;bersicht</h2>

<table class="table table-striped">
    <tr>
        {if $smarty.session.userdata.config.displayids eq "1"}<th>#</th>{/if}

        <th>Typenname</th>
        <th class="span1">Hintergrundfarbe</th>
        <th class="span1">Vordergrundfarbe</th>
        <th class="span2">Darstellung</th>
        <th class="span1">Bearbeiten</th>
    </tr>
{foreach $types as $type}
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
