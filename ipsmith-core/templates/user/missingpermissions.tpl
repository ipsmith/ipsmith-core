<h2>Fehlende Zugriffsrechte</h2>

<p>Um auf die von Ihnen geforderte Seite, oder Aktion, zugreifen zu können benötigen Sie gewisse Rechte. Nachfolgend finden Sie eine Übersicht, welche Rechte oder Rollen Ihnen fehlen. Bitte wenden Sie sich an den Zuständigen <a href="mailto:{$config.administratormail}">Administrator</a> um die Rechte anzufordern.</p>

{if $missingroles neq ""}
    <h3>Fehlende Rollen</h3>
    <p>Ihnen fehlen die folgenden Rollen:</p>

    <ul>
        {foreach $missingroles as $role}
            <li>{$role.name}</li>
        {/foreach}
    </ul>
{/if}