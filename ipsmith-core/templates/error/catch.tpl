<h1>Es trat ein Fehler auf!</h1>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Fehler:</strong><br /> {$smarty.session.lasterror->getMessage()}
                </div>
<pre>
{$smarty.session.lasterror}
</pre>
