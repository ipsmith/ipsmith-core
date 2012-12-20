<h2>Attribute</h2>
<p>Attribute können jedem Host-Eintrag zugeordnet werden. Sie können zum Beispiel Informationen wie eine Inventarnummer, Benutzernamen oder ähnliches enthalten. Man kann sie frei nach Wunsch definieren.</p>
<p>Sicherheitsfreigaben lauten wie folgt:
    <ul>
        <li>can_edit_attribut_$kurzname</li>
        <li>can_view_attribut_$kurzname</li>
        <li>can_delete_attribut_$kurzname</li>
        <li>can_assign_attribut_$kurzname</li>
    </ul>
</p>

<table class="table tablestriped sorttable">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Typ</th>
        <th>Zugewiesen zu</th>
        <th>&nbsp;</th>
    </tr>
    {foreach $attributes as $attribute}
        <tr>
            <td>{$attribute.id}</td>
            <td>{$attribute.name}</td>
            <td>{$attribute.type}</td>
            <td>{$attribute.count}</td>
            <td>&nbsp;</td>
        </tr>
    {/foreach}
</table>
