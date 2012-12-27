<h2>{$currentitemname} bearbeiten</h2>

<form method="POST" action="{$config.baseurl}/item/edithost.html">
    {if isset($currententry.id)}
    <input type="hidden" id="entry_id" name="entry_id" value="{$currententry.id}" />
    {/if}
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#itemgeneric" data-toggle="tab">Allgemein</a> </li>
        <li><a href="#itemcat" data-toggle="tab">Kategorisierung</a> </li>
        <li><a href="#itemexport" data-toggle="tab">Export-Einstellungen</a> </li>
        <li><a href="#itemlog" data-toggle="tab">Protokoll</a></li>
        <li><a href="#iteminfo" data-toggle="tab">Datensatz-Informationen</a></li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane" id="itemexport">

            <div class="row">
                <div class="span1">&nbsp;</div>
                <div class="span3" style="text-align:right;">
                    <select name="entry_exports[]" id="entry_exports" size="6" multiple="true">
                        {foreach $selectedexports as $export}
                        <option title="{$export.exportname}" rel="{$export.id}" value="{$export.id}">{$export.exportname}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="span2" style="text-align:center;">
                    <p>&nbsp;</p>
                    <p>
                        <button id="exportadd" class="btn btn-primary">&laquo; hinzufügen</button>
                    </p>

                    <p>
                        <button id="exportremove" class="btn btn-primary">entfernen &raquo;</button>
                    </p>
                        <script type="text/javascript">
                          $().ready(function() {
                           $('#exportadd').click(function() {
                            return !$('#exportavailable option:selected').remove().appendTo('#entry_exports');
                           });
                           $('#exportremove').click(function() {
                            return !$('#entry_exports option:selected').remove().appendTo('#exportavailable');
                           });
                          });
                        </script>
                </div>

                <div class="span3">
                        <select name="exportavailable[]" id="exportavailable" size="6" multiple="multiple">
                        {foreach $availableexports as $export}
                        <option title="{$export.exportname}" rel="{$export.id}" value="{$export.id}">{$export.exportname}</option>
                        {/foreach}
                        </select>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="itemcat">
            <table class="table table-striped ipsmith">
                <tr>
                    <th  class="span3">Einsatzort:</th>
                    <td>
                        <select id="entry_location" name="entry_location" >
                        {foreach $globallocations as $location}
                            <option  value="{$location.id}" {if $location.id eq $currententry.locationid}selected{/if}>{$location.humanname}</option>
                        {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Abteilung:</th>
                    <td>
                        <select id="entry_cat" name="entry_cat" >
                        {foreach $cats as $cat}
                            <option  value="{$cat.id}" {if $cat.id eq $currententry.catid}selected{/if}>{$cat.catname}</option>
                        {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Typ:</th>
                    <td>
                        <select id="entry_type" name="entry_type" >
                        {foreach $types as $type}
                            <option  value="{$type.id}" {if $type.id eq $currententry.typeid}selected{/if}>{$type.typename}</option>
                        {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Icon:</th>
                    <td>
                        <select id="entry_icon" name="entry_icon"  class="imagedropdown">
                        {foreach $icons as $icon}
                            <option style="background:url({$icon.path})  left top no-repeat #fff;padding-left:20px; "
                                    value="{$icon.path}" data-imagesrc="{$icon.path}"
                                    {if $currententry.iconpath neq "" && $currententry.iconpath eq $icon.path}selected{/if}>
                                    {$icon.name}
                            </option>
                        {/foreach}
                        </select>
                    </td>
                </tr>

            </table>
        </div>
        <div class="tab-pane active" id="itemgeneric">
            <table class="table table-striped ipsmith">
                <tr>
                    <th  class="span3">MAC-Adresse:</th>
                    <td>
                        <div class="input-append">
                        <input type="text" id="entry_mac" name="entry_mac" value="{if isset($currententry.macaddress)}{$currententry.macaddress}{/if}" placeholder="00:00:00:00:00:00" class="span3" style="text-transform:capitalize; ">
                        <span class="add-on">
                             <a data-original-title="Hinweis" href="#"  rel="popover" data-placement="right" data-content="Bitte geben Sie die Adresse entweder im Format '00:00:00:00:00:00' oder '00-00-00-00-00-00' ein."><i class="icon-question-sign"></i></a>
                        </span>
                    </div>
                    </td>
                </tr>
                <tr>
                    <th>IP-Adresse:</th>
                    <td><input type="text" id="entry_ip" name="entry_ip" value="{if isset($currententry.ip)}{$currententry.ip}{/if}" placeholder="127.0.0.1"  class="span3"></td>
                </tr>
                <tr>
                    <th>Primärer Host-Name:</th>
                    <td>
                        <div class="input-append">
                        <input type="text" id="entry_hostname" name="entry_hostname" value="{if isset($currententry.hostname)}{$currententry.hostname}{/if}" placeholder="localhost"  class="span3">
                        <span class="add-on">
                             <a data-original-title="Hinweis" href="#"  rel="popover" data-placement="right" data-content="Sie können später weitere Hostnamen hinzufügen. Bitte tragen Sie hier <strong>keinen</strong> Fully Qualified Domain Name (FQDN) ein."><i class="icon-question-sign"></i></a>
                        </span>
                        </div>
                        </td>
                </tr>
                <tr>
                    <th>Darstellung in Listen:</th>
                    <td>
             <div class="input-append color" data-color="rgb({if isset($currententry.color_r)}{$currententry.color_r}{else}0{/if}, {if isset($currententry.color_g)}{$currententry.color_g}{else}0{/if}, {if isset($currententry.color_b)}{$currententry.color_b}{else}0{/if})" data-color-format="hex" id="cp3">
                <input class="span3" value="{if isset($currententry.color_hex)}{$currententry.color_hex}{/if}" readonly="" type="text" id="entry_color_hex" name="entry_color_hex">
                <span class="add-on"><i style="background-color: {if isset($currententry.color)}{$currententry.color}{else}#000000{/if}"></i></span>
            </div>
                    </td>
                </tr>


            </table>
        </div>
        <div class="tab-pane" id="itemlog">
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
        <div class="tab-pane" id="iteminfo">
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
                    <td>{if isset($currententry.updatedat)}{$currententry.updatedat}{else}-{/if}</td>
                </tr>
                <tr>
                    <th>Bearbeitet von:</th>
                    <td>{if isset($currententry.updatedby)}{$currententry.updatedby}{else}-{/if}</td>
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
});
</script>