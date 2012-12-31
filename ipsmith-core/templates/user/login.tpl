<style type="text/css">
.form-signin {
  max-width: 300px;
  padding: 19px 29px 29px;
  margin: 0 auto 20px;
  background-color: #fff;
  border: 1px solid #e5e5e5;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
  -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
  box-shadow: 0 1px 2px rgba(0,0,0,.05);
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin input[type="text"],
.form-signin input[type="password"] {
  font-size: 16px;
  height: auto;
  margin-bottom: 15px;
  padding: 7px 9px;
}

</style>

<form class="form-signin" method="POST" action="{$config.baseurl}/user/login.html">
  <h2 class="form-signin-heading">Bitte melden Sie sich an.</h2>
  <input id="username" name="username" type="text" class="input-block-level" placeholder="Benutzername">
  <input id="password" name="password" type="password" class="input-block-level" placeholder="Kennwort">
  <button class="btn btn-large btn-primary" type="submit" id="submit" name="submit">Anmelden</button>
</form>
