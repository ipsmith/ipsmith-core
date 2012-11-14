    <div class="navbar navbar-inverse navbar-fixed-top">
           <div class="navbar-inner">
             <div class="container-fluid">
               <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </a>
               <a class="brand" href="#">IPSmith</a>
              <div class="nav-collapse collapse">
                 <p class="navbar-text pull-right">
                   {if $smarty.session.userdata.id eq 0}
                   <a href="{$config.baseurl}/user/login.html">Bitte melden Sie sich an.</a>
                   {else}
                   Angemeldet als <a href="#" class="navbar-link">{$smarty.session.userdata.username}</a>
                   {/if}
                 </p>
                 <ul class="nav">
                   <li class="active"><a href="#">Home</a></li>
                   <li><a href="{$config.baseurl}/about/info.html">About</a></li>
                   <li><a href="#contact">Contact</a></li>
                 </ul>
               </div><!--/.nav-collapse -->
             </div>
           </div>
         </div>

