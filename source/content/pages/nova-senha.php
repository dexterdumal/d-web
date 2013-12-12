<?php
	global $msg;
	global $args;
	$statusForm=0;
	//print_r($_POST);
?>
<?php
if((isset($_POST['nova_senha'])) && (isset($_POST['confirm-nova-senha'])) && ($_POST['nova_senha']==$_POST['confirm-nova-senha'])){
	$responsavel = new Responsavel();
	$hash = $_POST['hash'];
	$novaSenha = $_POST['nova_senha'];
	$arrayRetorno = $responsavel->atualizaSenhaResponsavel($hash, $novaSenha);
	if($arrayRetorno['statusMsg']['status']=="true"){
		$msg = array('class'=>'sucesso','msg'=>'Senha atualizada com sucesso!'); $statusForm = 3;
	}else{
		$msg = array('class'=>'erro','msg'=>'Ocorreu um erro ao tentar atualizar a sua senha');
	}
}else{
	$hash = $args[1];
	$dadosResp = Security::loginUncrypt($hash);
	if($dadosResp['numRows']==0){
		$msg = array('class'=>'erro','msg'=>'Usuário não encontrado'); $statusForm = 2;
	}
}
?>
<?php 
?>
<h2 class="titulo-internas">Nova Senha</h2>
<div id="msg" class="<?php print $msg['class'];?>"><?php print $msg['msg'];?></div>
<?php if($statusForm==0):?>
<form id="frm_nova_senha" action="<?php print basePath;?>nova-senha" method="post">
	<p><label for="nova-senha">Informe sua nova senha</label><input type="password" name="nova_senha" id="nova-senha"/></p>
	<p><label for="confirm-nova-senha">Digite novamente sua senha</label><input type="password" name="confirm-nova-senha" id="confirm-nova-senha"/></p>
	<p>
		<input type="hidden" name="hash" value="<?php print $args[1];?>"/>
		<input type="submit" id="btnEnviar" name="btnEnviar" value="gravar nova senha"/>
	</p>
</form>
<?php elseif($statusForm==2):?>
	<br />
	<p>
		O usuário informado não foi encontrado ou a senha está incorreta.
		<br/>Caso você tenha chegado até esta página através do link de recuperação de senha no seu email, este link pode já ter expirado ou a senha ter sido modificada anteriormente.
		<br/><br/><br/><br/>
		<a href="<?php print basePath;?>login?pa=1" class="btn">Clique aqui para acessar a página de login e solicitar o reenvio de uma nova senha</a>
	</p>	
<?php else:?>
	<p><strong>Sua senha foi alterada com sucesso, clique no link 'Inscrição' no menu ao lado caso queira cadastrar uma criança.</strong></p>
<?php endif;?>
