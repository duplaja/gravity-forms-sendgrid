<?php

add_filter( 'gform_addon_navigation', 'sendGridPage' );

function sendGridPage( $menus ) {
  $menus[] = array( 'name' => 'SendGrid', 'label' => __( 'SendGrid Api Key' ), 'callback' =>  'sendGridPageRender' );
  return $menus;
}

function sendGridPageRender(){
	$key = get_option('sendgrid-api-key');
	$remententeMail = get_option('rementente-mail');
	$remententeName = get_option('rementente-name');
 	?>
		<div class='wrap'>
			<h2>SendGrid Api Key - Configurações</h2>
				<form id="sendgrid-form">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<label for="sendgrid-api-key-field">Api Key</label>
								</th>
								<td>
									<input name="sendgrid-api-key" type="password" id="sendgrid-api-key-field" value="<?php echo isset($key)? $key : '' ?>" class="regular-text">
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="rementente-name">Nome Remetente</label>
								</th>
								<td>
									<input name="rementente-name" type="text" id="rementente-name" value="<?php echo isset($remententeName)? $remententeName : '' ?>" class="regular-text">
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="rementente-mail">E-mail Remetente</label>
								</th>
								<td>
									<input name="rementente-mail" type="text" id="rementente-mail" value="<?php echo isset($remententeMail)? $remententeMail : '' ?>" class="regular-text">
								</td>
							</tr>
							
						</tbody>
					</table>
				</form>
				<p class="submit"><button type="button" name="submit" id="btn-save-apikey" class="button button-primary">Salvar</button></p>
			</form>
		</div>
	<?php
}



