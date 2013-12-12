<?php
	global $msg;
	$statusForm=0;
	if(isset($_POST['cpf'])){
		$responsavel = new Responsavel();
		$dadosResponsavel = $responsavel->obterDetalhesResponsavel(Security::limpaEspeciais($_POST['cpf']));
		if($dadosResponsavel['numRows']>0){
			$body ="<h2>Recuperação de senha</h2>";
			$body .="				<div>";
			$body .="					<p>Conforme solicitado segue abaixo o link para criação de nova senha de acesso à área administrativa</p>";
			$body .="					<p><a href='".basePath."nova-senha/".SHA1($dadosResponsavel['data'][0]['cpf'].$dadosResponsavel['data'][0]['senha'])."'>[Clique aqui para alterar sua senha]</a></p>";
			$body .="				</div>";
			
			$to = array(
						"email"=>$dadosResponsavel['data'][0]['email'],
						"nome"=>$dadosResponsavel['data'][0]['nome_responsavel']);
			$subject = "Recuperação de senha";
			$mailer = new Mailer();
			if($mailer->sendMail($to,$subject,$body)){$msg['msg']="Sua nova senha foi reenviada para o email cadastrado"; $msg['class'] = "sucesso"; $statusForm=1;}
			else{$msg['msg']="Ocorreu um erro ao tentar reenviar sua senha. Por favor tente novamente e, caso o problema persista, entre em contato";$msg['class'] = "erro"; $statusForm=0;}
		}else{$msg['msg']="Nenhum usuário encontrado com este número de CPF";$msg['class'] = "erro"; $statusForm=0;}
	}
?>
<h2 class="titulo-internas">Lembrar Senha</h2>
<div id="msg" class="<?php print $msg['class'];?>"><?php print $msg['msg'];?></div>
<?php if($statusForm==0):?>
<form id="frm_lembrar_senha" action="lembrar-senha" method="post">
	<p><label for="cpf">Informe abaixo, o número de cpf utilizado no cadastro do responsável, para que seja enviado o link para recuperação de senha para o email cadastrado:</label></p>
	<p><input type="text" name="cpf" id="cpf" class="text mask-cpf"/><input type="submit" id="btnEnviar" name="btnEnviar" value="enviar"/></p>
</form>
<?php else:?>
	<p>Dentro de alguns instantes, você deverá receber em sua caixa de emails, o endereço para redefinir a sua senha.</p>
<?php endif;?>
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