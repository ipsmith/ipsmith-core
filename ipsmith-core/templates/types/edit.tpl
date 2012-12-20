<h2>{$currentitemname} bearbeiten</h2>

<form method="POST" action="{$config.baseurl}/types/edit.html">
    {if isset($currententry.id)}
    <input type="hidden" id="type_id" name="type_id" value="{$currententry.id}" />
    {/if}
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#typegeneric" data-toggle="tab">Allgemein</a> </li>
        <li><a href="#typelog" data-toggle="tab">Protokoll</a></li>
        <li><a href="#typeinfo" data-toggle="tab">Datensatz-Informationen</a></li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane active" id="typegeneric">
            <table class="table table-striped ipsmith">
                <tr>
                    <th  class="span3">Name:</th>
                    <td>
                        <input type="text" id="type_typename" name="type_typename" value="{if isset($currententry.typename)}{$currententry.typename}{/if}" placeholder="Name..." class="span3" >
                    </td>
                </tr>
                <tr>
                    <th>Hintergrund-Farbe:</th>
                    <td>
             <div class="input-append color" data-color="rgb({if isset($currententry.bgcolor_r)}{$currententry.bgcolor_r}{else}0{/if}, {if isset($currententry.bgcolor_g)}{$currententry.bgcolor_g}{else}0{/if}, {if isset($currententry.bgcolor_b)}{$currententry.bgcolor_b}{else}0{/if})" data-color-format="hex" id="cp3">
                <input class="span3" value="{if isset($currententry.bgcolor_hex)}{$currententry.bgcolor_hex}{/if}" readonly="" type="text" id="type_bgcolor_hex" name="type_bgcolor_hex">
                <span class="add-on"><i style="background-color: {if isset($currententry.bgcolor_hex)}{$currententry.bgcolor_hex}{else}#000000{/if}"></i></span>
            </div>
                    </td>
                </tr>
                <tr>
                    <th>Schrift-Farbe:</th>
                    <td>
             <div class="input-append color" data-color="rgb({if isset($currententry.fgcolor_r)}{$currententry.fgcolor_r}{else}0{/if}, {if isset($currententry.fgcolor_g)}{$currententry.fgcolor_g}{else}0{/if}, {if isset($currententry.fgcolor_b)}{$currententry.fgcolor_b}{else}0{/if})" data-color-format="hex" id="cp4">
                <input class="span3" value="{if isset($currententry.fgcolor_hex)}{$currententry.fgcolor_hex}{/if}" readonly="" type="text" id="type_fgcolor_hex" name="type_fgcolor_hex">
                <span class="add-on"><i style="background-color: {if isset($currententry.fgcolor_hex)}{$currententry.fgcolor_hex}{else}#000000{/if}"></i></span>
            </div>
                    </td>
                </tr>


            </table>
        </div>
        <div class="tab-pane" id="typelog">
            <table class="table table-striped">
                <tr>
                    <th class="span1">#ID</th>
                    <th class="span2">Benutzer</th>
                    <th>Aktion</th>
                    <th class="span3">Zeitpunkt</th>
                </tr>
                {if isset($logentries)}
                    {foreach $logentries as $log}
                        <tr>
                            <td>{$log.id}</td>
                            <td>{$log.username}</td>
                            <td>{$log.action}</td>
                            <td>{$log.datecreated}</td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Keine Datensätze gefunden</td>
                        <td>&nbsp;</td>
                    </tr>
                {/if}
            </table>
        </div>
        <div class="tab-pane" id="typeinfo">
            <table class="table table-striped ipsmith">
                <tr>
                    <th class="span3">ID:</th>
                    <td>{if isset($currententry.id)}{$currententry.id}{else}-{/if}</td>
                </tr>
                <tr>
                    <th>Guid:</th>
                    <td>{if isset($currententry.guid)}{$currententry.guid}{else}-{/if}</td>
                </tr>

                <tr>
                    <th>Erstellt:</th>
                    <td>{if isset($currententry.createdat)}{$currententry.createdat}{else}-{/if}</td>
                </tr>
                <tr>
                    <th>Erstellt von:</th>
                    <td>{if isset($currententry.createdby)}{$currententry.createdby}{else}-{/if}</td>
                </tr>
                <tr>
                    <th>Bearbeitet:</th>
                    <td>{if isset($currententry.modifiedat)}{$currententry.modifiedat}{else}-{/if}</td>
                </tr>
                <tr>
                    <th>Bearbeitet von:</th>
                    <td>{if isset($currententry.modifiedby)}{$currententry.modifiedby}{else}-{/if}</td>
                </tr>

</table>
        </div>
    </div>
</div>

<p>
<input type="submit" class="btn btn-success" type="button" id="submit" name="submit" value="Speichern" />
<button class="btn btn-danger" type="button">Abbrechen</button>
</p>
</form>

<script src="{$config.baseurl}/assets/colorpicker/js/bootstrap-colorpicker.js"></script>

<script>
$(function(){

    $('#cp3').colorpicker().on('changeColor', function(ev){
        bodyStyle.backgroundColor = ev.color.toHex();
    });
    $('#cp4').colorpicker().on('changeColor', function(ev){
        bodyStyle.backgroundColor = ev.color.toHex();
    });
    });
</script>