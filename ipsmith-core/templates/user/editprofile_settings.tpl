<div class="tab-pane" id="itemsettings">
    <table class="table table-striped ipsmith">
        <tr>
            <th class="span4">Überschriften wiederholen:</th>
            <td>
                <div class="controls controls-row">
                    <input class="span1" type="text" id="usersettings_repeatheaders" name="usersettings_repeatheaders" value="{if isset($currententry.config.repeatheaders)}{$currententry.config.repeatheaders}{/if}" />
                </div>
            </td> 
        </tr>
        <tr>
            <th class="span4">Sprechende Namen anzeigen:</th>
            <td>
                <div class="controls controls-row">
                    <select class="span2" id="usersettings_usehumanname" name="usersettings_usehumanname">
                           <option value="1" {if $currententry.config.usehumanname eq "1"}selected{/if}>Ja</option>
                           <option value="0" {if $currententry.config.usehumanname eq "0"}selected{/if}>Nein</option>
                    </select>
                </div>
            </td> 
        </tr>
        <tr>
            <th class="span4">Numerische IDs anzeigen:</th>
            <td>
                <div class="controls controls-row">
                    <select class="span2" id="usersettings_displayids" name="usersettings_displayids">
                           <option value="1" {if $currententry.config.displayids eq "1"}selected{/if}>Ja</option>
                           <option value="0" {if $currententry.config.displayids eq "0"}selected{/if}>Nein</option>
                    </select>
                </div>
            </td> 
        </tr>
    </table>
</div>