<?php
	global $msg;
	if(isset($_SESSION["logado"])):
		print "<span class='welcome-message'>Seja bem vindo(a), ".(isset($_SESSION['nomeUsuario'])?$_SESSION['nomeUsuario']."!":"visitante!")."</span>";
		print "<a href='".basePath."logout'>(sair)</a>";
	else:
?>
<form id="frm-login" action="<?php print basePath;?>login?next=<?php print args;?>" method="post">
	<div id="dados-login">
		<label for="login">CPF:</label><input type="text" id="login" name="login" size="15" maxlength="14" class="text mask-cpf"/>
		<label for="senha">Senha:</label><input type="password" id="senha" name="senha" size="15" maxlength="50" class="text"/>
		<input type="hidden" name="acao" value="login"/>
		<!--a href="<?php print basePath;?>lembrar-senha" title="clique aqui se você esqueceu sua senha de acesso">[Esqueci minha senha]</a-->
		<input type="submit" name="btnEnviar" value="entrar" id="btnEnviar" class="btn"/>
	</div>
</form>
<script type="text/javascript">
(function($) {
	$(function() {
		$('.mask-cpf').mask('999.999.999-99'); //CPF
	});
})(jQuery);
</script>
<?php endif;?>