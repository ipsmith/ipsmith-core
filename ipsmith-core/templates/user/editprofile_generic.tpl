<div class="tab-pane active" id="itemgeneric">
    <table class="table table-striped ipsmith">
        <tr>
            <th class="span3">Benutzername:<br /><small>(Kann nicht geändert werden.)</small></th>
            <td>
                <div class="controls controls-row">
                    <span class="input-large uneditable-input span3">{if isset($currententry.username)}{$currententry.username}{/if}</span>
                </div>
            </td> 
        </tr>
        <tr>
            <th  class="span3">Vor- / Nachname:</th>
            <td>
                <div class="controls controls-row">
                <input class="span3" type="text" id="user_firstname" name="user_firstname" value="{if isset($currententry.firstname)}{$currententry.firstname}{/if}">
                <input class="span3" type="text" id="user_lastname" name="user_lastname" value="{if isset($currententry.lastname)}{$currententry.lastname}{/if}">
            </div>
            </td> 
        </tr>
        <tr>
            <th>E-Mail-Adresse:</th>
            <td>
                <div class="controls controls-row">
                    <input type="text" id="user_email" name="user_email" value="{if isset($currententry.email)}{$currententry.email}{/if}" class="span6">
                </div>
            </td>
        </tr>
        <tr>
            <th>API-Schlüssel:<br /></th>
            <td>
                
                    <span class="uneditable-input input-xxlarge">{if isset($currententry.apikey)}{$currententry.apikey}{/if}</span><br />(Kann durch Kennwort-Änderung aktualisiert werden.)
                
            </td>
        </tr>
    </table>
</div>