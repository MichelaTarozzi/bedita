<div id="containerPage">
	<div class="FormPageHeader"><h1>{t}Users admin{/t}</h1></div>
	<div id="mainForm">
		<form action="{$html->url('/admin/users')}" method="post" name="userForm" id="userForm">
		<table border="0" cellspacing="8" cellpadding="0">
		<thead><tr><th>{t}User{/t}</th><th>{t}Name{/t}</th></tr></thead>
		<tbody>
		{foreach from=$users item=u}
		<tr><td>{$u.User.userid}</td><td>{$u.User.realname}</td></tr>
  		{/foreach}
  		</tbody>
		</table>
		</form>
	</div>
</div>