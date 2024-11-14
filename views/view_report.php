<section id="view_report" class="pb-5">
	<div class="row flex-column no-gutters justify-content-center align-items-center">
		<?php if (isset($_SESSION["submitted"])) : ?>
			<h1>Thank you, the report was submitted successfully</h1>
			<h4></br>The report has been sent to <b><?php echo $police_force_name; ?></b> with the incident reference <b><?php echo $incident_id; ?></b></h4>

			<p class="mt-5">Use the incident reference in the login form to view your report at later date.</p>
			<p class="mt-5">Here is the report you submitted.</p>
		<?php else : ?>
			<h4>The report is in the hands of <b><?php echo $police_force_name; ?></b>.
			<br>This incident report reference is: <b><?php echo $incident_id; ?></b></h4>
		<?php endif; ?>

	</div>

	<div class="submitted-report container mx-auto row">
		<div class="your_details col-6">
			<h3 class="my-4">Your Details</h3>
			<p>Name: <?php echo "$firstname $lastname"; ?></p>

			<?php
			$address = "$address_line_1, ";

			if (!empty($address_line_2)) {
				$address .= "$address_line_2, ";
			}
			$address .= "$town_city, $county, $postcode";
			?>

			<p class="w-75">Address: <?php echo $address; ?></p>
			<p>Email: <?php echo $email; ?></p>
			<p>Mobile: <?php echo $mobile_number; ?></p>
			<?php if (!empty($phone_number)) : ?>
				<p>Phone: <?php echo $phone_number; ?></p>
			<?php endif; ?>

			<?php $dob = date("d/m/Y", strtotime($dob)); ?>
			<p>Date of Birth: <?php echo $dob; ?></p>
			<p>Preferred Method of Contact: <?php echo $pref_contact_method; ?></p>
		</div>

		<div class="incident_details col-6">
			<h3 class="my-4">Incident Details</h3>

			<p>Location of incident: <?php echo "$location_rd_city, $location_county"; ?></p>

			<?php $date_time = date("d/m/Y H:i", strtotime($date_time)); ?>
			<p>Date and Time of incident: <?php echo $date_time; ?></p>
			<div class="offending-vehicle">
				<p>Offending Vehicle:</p>
				<div class="row ml-3">
					<div class="col">
						<p>Make: <?php echo $vehicle_make; ?></p>
						<p>Model: <?php echo $vehicle_model; ?></p>
					</div>
					<div class="col">
						<p>Colour: <?php echo $vehicle_colour; ?></p>
						<p>Registration: <?php echo $vehicle_reg; ?></p>
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<p class="col-4">The vehicle in relation to you:</p>
				<div class="col">
					<?php echo $location_details_in_relation; ?>
				</div>
			</div>
			<div class="row mt-4">
				<p class="col-4">The incident description:</p>
				<div class="col">
					<?php echo $incident_description; ?>
				</div>
			</div>
			<p>Your camera was: <?php echo $camera_fitting_name; ?></p>
		</div>
	</div>
	<div class="row no-gutters justify-content-center">
		<div class="footage_evidence col-6">
			<h3 class="my-4">Footage Evidence</h3>
			<video class="w-100" src="<?php echo "/uploads/$footage_filename"; ?>" controls></video>
		</div>
	</div>
</section>
