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
            <td>{if isset($currententry.modifiedat)}{$currententry.modifiedat}{else}-{/if}</td>
        </tr>
        <tr>
            <th>Bearbeitet von:</th>
            <td>{if isset($currententry.modifiedby)}{$currententry.modifiedby}{else}-{/if}</td>
        </tr>
    </table>
</div>