<h2>Eigenes Profil <small>bearbeiten</small></h2>

<form method="POST" action="{$config.baseurl}/user/editprofile.html">

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#itemgeneric" data-toggle="tab">Benutzerdaten</a> </li>
        <li><a href="#itempassword" data-toggle="tab">Kennwort</a></li>
        <li><a href="#itemsettings" data-toggle="tab">Einstellungen</a></li>
        <li><a href="#itemlog" data-toggle="tab">Protokoll</a></li>
        <li><a href="#iteminfo" data-toggle="tab">Datensatz-Informationen</a></li>
    </ul>

    <div class="tab-content">

        {include file="$currentModule/editprofile_generic.tpl"}
        {include file="$currentModule/editprofile_password.tpl"}
        {include file="$currentModule/editprofile_settings.tpl"}
        {include file="$currentModule/editprofile_log.tpl"}
        {include file="$currentModule/editprofile_info.tpl"}
    </div>
</div>

<p>
<input type="submit" class="btn btn-success" type="button" id="submit" name="submit" value="Speichern" />
<button class="btn btn-danger" type="button">Abbrechen</button>
</p>
</form>