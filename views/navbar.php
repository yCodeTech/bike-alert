<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container-fluid">
		<a class="navbar-brand" href="./"><?php echo SITE_NAME ?></a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-around " id="navbarNav">
			<ul class="navbar-nav">
				<?php echo create_nav_items(); ?>
			</ul>
		</div>
	</div>
</nav>
