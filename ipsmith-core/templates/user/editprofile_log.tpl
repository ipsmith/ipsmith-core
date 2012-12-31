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