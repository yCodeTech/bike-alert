<section id="login" class="row no-gutters flex-column justify-content-center">

	<?php if (isset($_SESSION["loggedout"])) : ?>
		<div class="alert alert-success w-25 mx-auto text-center">You've been logged out.</div>
	<?php endif;?>
	<?php session_unset(); ?>

	<h1 class="text-center m-3">Login</h1>
	<div class="row justify-content-center no-gutters mt-5">

		<form action="/login_submit" method="post" id="login">
		<div class="row flex-column no-gutters">
			<div class="row justify-content-center">
				<div class="form-group col-auto">
					<label for="incident_ref" class="required">Incident Reference</label>

					<input type="text" name="login[incident_ref]" id="incident_ref" class="form-control" value="<?php echo $postback_value["incident_ref"] ?? "" ?>">
				</div>
				<div class="form-group col-auto align-self-end">
					<button type="submit" class="btn btn-primary" name="report[submit]">Login</button>
				</div>
			</div>

			<?php echo $errors["incident_ref"] ?? ""; ?>
		</div>
		

		</form>
	</div>

</section>
