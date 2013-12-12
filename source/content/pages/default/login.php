<?php
	global $msg;
?>
<h2 class="titulo-internas">Login</h2>
<form id="frm-login" action="<?php print basePath;?>login?next=<?php print args;?>" method="post">
<div id="msg" class="<?php print $msg['class'];?>"><?php print $msg['msg'];?></div>
	<fieldset id="dados-login">
		<legend>Dados do Usuário</legend>
		<label for="login">CPF:</label><input type="text" id="login" name="login" size="15" maxlength="14" class="text mask-cpf"/>
		<label for="senha">Senha:</label><input type="password" id="senha" name="senha" size="15" maxlength="50" class="text"/>
		<input type="hidden" name="papelLogin" value="<?php print $papelLogin;?>"/>
		<input type="hidden" name="acao" value="login"/>
		<a href="<?php print basePath;?>lembrar-senha" title="clique aqui se você esqueceu sua senha de acesso">[Esqueci minha senha]</a>
	</fieldset>
	<p class="centralizado"><input type="submit" name="btnEnviar" value="entrar" id="btnEnviar" class="btn"/></p>
</form>
<script type="text/javascript">
(function($) {
	$(function() {
		$('.mask-data').mask('99/99/9999'); //data
		$('.mask-hora').mask('99:99'); //hora
		$('.mask-fone').mask('9999-9999'); //telefone
		$('.mask-rg').mask('99.999.999-9'); //RG
		$('.mask-cpf').mask('999.999.999-99'); //CPF
		$('.mask-cep').mask('99.999-999'); //CPF
		//$('.mask-dre').mask('999999999'); //DRE
	});
})(jQuery);
</script>