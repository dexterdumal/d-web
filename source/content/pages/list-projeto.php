<h2 class="titulo-internas">Lista de Projetos</h2>
<div id="box-filtros">
	<form id="frm-filtro-busca" method="post" action="<?php print args; ?>">
		<fieldset id="filtros-busca">
			<legend>Buscar por:</legend>
			<select id="sel-tipo-filtro" name="sel_tipo_filtro">
				<option value="todos"> ------- </option>
				<option value="titulo_projeto">Título</option>
				<option value="professor">Professor</option>
				<option value="departamento">Departamento</option>
				<option value="assunto">Assunto</option>
			</select>
			<input type="text" id="txt-termo" name="txt_termo" size="80" maxlength="200" />
			<input type="submit" name="btnEnviar" value="buscar" id="btn-enviar" class="btn"/>
		</fieldset>
	</form>
</div>
<div id="box-resultados"></div>