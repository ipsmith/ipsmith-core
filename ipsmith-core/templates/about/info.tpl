
<table class="table table-striped">
<tbody>
    <tr>
        <th class="span4">Ihre Version:</th>
        <td>{$system.currentversion} {$system.versiontype}</td>
    </tr>
    <tr>
        <th>Verfuegbare Version:</th>
        <td>{$system.latestversion.versionnumber} {$system.latestversion.versiontype}{if $system.newerversion eq "1"}<br /> <span class="label label-important">Neuere Version verf&uuml;gbar</span> {/if}</td>
    </tr>
    <tr>
        <th>Anzahl an Eintr&auml;gen:</th>
        <td>{$system.entries}</td>
        </tr>
</tbody>
</table>
