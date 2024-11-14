<section id="report" class="pb-5">
	<h1 class="text-center m-3">Report a Traffic Incident</h1>
	<p class="text-center required mb-5">Note: Fields that are required are denoted with a:</p>

	<div class="row justify-content-center no-gutters">
		<form action="/report_submit" method="post" enctype="multipart/form-data" id="reportTrafficIncident" class="col-6">
			<div class="your_details">
				<h2 class="mb-4">Your Details</h2>
			
				<div class="row">
					<div class="form-group col">
						<label for="firstname" class="required">Firstname</label>
						<input type="text" name="report[firstname]" id="firstname" class="form-control" value="<?php echo $postback_value["firstname"] ?? "" ?>">
						<?php echo $errors["firstname"] ?? ""; ?>
					</div>
					<div class="form-group col">
						<label for="lastname" class="required">Lastname</label>
						<input type="text" name="report[lastname]" id="lastname" class="form-control" value="<?php echo $postback_value["lastname"] ?? "" ?>">
						<?php echo $errors["lastname"] ?? ""; ?>

					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label for="address1" class="required">Address Line 1</label>
						<input type="text" name="report[address_line_1]" id="address1" class="form-control" value="<?php echo $postback_value["address_line_1"] ?? "" ?>">
						<?php echo $errors["address_line_1"] ?? ""; ?>

					</div>
					<div class="form-group col">
						<label for="address2">Address Line 2</label>
						<input type="text" name="report[address_line_2]" id="address2" class="form-control" value="<?php echo $postback_value["address_line_2"] ?? "" ?>">
					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label for="town_or_city" class="required">Town/City</label>
						<input type="text" name="report[town_or_city]" id="town_or_city" class="form-control" value="<?php echo $postback_value["town_or_city"] ?? "" ?>">
						<?php echo $errors["town_or_city"] ?? ""; ?>

					</div>
					<div class="form-group col">
						<label for="county" class="required">County</label>
						<input type="text" name="report[county]" id="county" class="form-control" value="<?php echo $postback_value["county"] ?? "" ?>">
						<?php echo $errors["county"] ?? ""; ?>

					</div>
					<div class="form-group col">
						<label for="postcode" class="required">Postcode</label>
						<input type="text" name="report[postcode]" id="postcode" class="form-control" value="<?php echo $postback_value["postcode"] ?? "" ?>">
						<?php echo $errors["postcode"] ?? ""; ?>

					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label for="email" class="required">Email</label>
						<input type="text" name="report[email]" id="email" class="form-control" value="<?php echo $postback_value["email"] ?? "" ?>">
						<?php echo $errors["email"] ?? ""; ?>

					</div>
					<div class="form-group col">
						<label for="mobile" class="required">Mobile</label>
						<input type="text" name="report[mobile]" id="mobile" class="form-control" value="<?php echo $postback_value["mobile"] ?? "" ?>">
						<?php echo $errors["mobile"] ?? ""; ?>
					</div>
					<div class="form-group col">
						<label for="phone">Home Phone</label>
						<input type="text" name="report[phone]" id="phone" class="form-control" value="<?php echo $postback_value["phone"] ?? "" ?>">
						<?php echo $errors["phone"] ?? ""; ?>
						
					</div>
					
				</div>
				<div class="row">
					<div class="form-group col">
						<label for="date_of_birth" class="required">Date of Birth</label>
						<input type="date" name="report[date_of_birth]" id="date_of_birth" class="form-control" value="<?php echo $postback_value["date_of_birth"] ?? "" ?>" max="9999-12-31">
						<?php echo $errors["date_of_birth"] ?? ""; ?>
					</div>
					<div class="form-group col">
						<label for="preferred_contact_method" class="required">Preferred Contact Method</label>

						<?php $pref_val = $postback_value["preferred_contact_method"] ?? "";?>

						<select name="report[preferred_contact_method]" id="preferred_contact_method" class="custom-select">
							<option value="" selected>Select your preferred contact method</option>
							<option value="email" <?php echo is_selected("email", $pref_val); ?>>Email</option>
							<option value="phone" <?php echo is_selected("phone", $pref_val); ?>>Phone</option>
							<option value="mobile" <?php echo is_selected("mobile", $pref_val); ?>>Mobile</option>
							<option value="post" <?php echo is_selected("post", $pref_val); ?>>Post</option>
						</select>
						<?php echo $errors["preferred_contact_method"] ?? ""; ?>
					</div>
				</div>
			</div>
			<div class="the_incident mt-5">
				<h2 class="mb-4">Incident Details</h2>
				<div class="row">
					<div class="form-group col">
						<label for="incident_location" class="required">Location (Road and Town/City)</label>
						<input type="text" name="report[incident_location]" id="incident_location" class="form-control" value="<?php echo $postback_value["incident_location"] ?? "" ?>">
						<?php echo $errors["incident_location"] ?? ""; ?>
					</div>
					<div class="form-group col">
						<label for="incident_county" class="required">County</label>
						<input type="text" name="report[incident_county]" id="incident_county" class="form-control" value="<?php echo $postback_value["incident_county"] ?? "" ?>">
						<?php echo $errors["incident_county"] ?? ""; ?>
					</div>
				</div>
				<div class="row">
					<div class="form-group col">
					<label for="police_force_output">Police force to connect to...</label>
					<input type="text" name="report[police_force_name]" id="police_force_output" class="form-control disabled" readonly value="<?php echo $postback_value["police_force_name"] ?? "" ?>">
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col">
						<label for="date_time" class="required">Date and Time</label>
						<input type="datetime-local" name="report[date_time]" id="date_time" class="form-control" value="<?php echo $postback_value["date_time"] ?? "" ?>" max="9999-12-31T23:59">
						<?php echo $errors["date_time"] ?? ""; ?>
					</div>
				</div>

				<h5>The offending vehicle</h5>
				<div class="row">
					<div class="form-group col">
						<label for="vehicle_make">Make (if known)</label>
						<input type="text" name="report[vehicle_make]" id="vehicle_make" class="form-control" value="<?php echo $postback_value["vehicle_make"] ?? "" ?>">
						<?php echo $errors["vehicle_make"] ?? ""; ?>

					</div>
					<div class="form-group col">
						<label for="vehicle_model">Model (if known)</label>
						<input type="text" name="report[vehicle_model]" id="vehicle_model" class="form-control" value="<?php echo $postback_value["vehicle_model"] ?? "" ?>">
						<?php echo $errors["vehicle_model"] ?? ""; ?>

					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label for="vehicle_reg" class="required">Registration</label>
						<input type="text" name="report[vehicle_registration]" id="vehicle_reg" class="form-control" value="<?php echo $postback_value["vehicle_registration"] ?? "" ?>">
						<?php echo $errors["vehicle_registration"] ?? ""; ?>
					</div>
					<div class="form-group col">
						<label for="vehicle_colour">Colour (if known)</label>
						<input type="text" name="report[vehicle_colour]" id="vehicle_colour" class="form-control" value="<?php echo $postback_value["vehicle_colour"] ?? "" ?>">
						<?php echo $errors["vehicle_colour"] ?? ""; ?>

					</div>
				</div>
				<div class="row align-items-baseline">
					<div class="form-group col">
						<label for="vehicle_location_in_relation" class="required">Where was the vehicle in relation to you when the incident took place?</label>
						<textarea name="report[vehicle_location_in_relation]" id="vehicle_location_in_relation" cols="30" rows="10" class="form-control" ><?php echo $postback_value["vehicle_location_in_relation"] ?? "" ?></textarea>
						<?php echo $errors["vehicle_location_in_relation"] ?? ""; ?>
					</div>
					<div class="form-group col">
						<label for="incident_description" class="required">Describe in detail what happened</label>
						<textarea name="report[incident_description]" id="incident_description" cols="30" rows="10" class="form-control"><?php echo $postback_value["incident_description"] ?? "" ?></textarea>
						<?php echo $errors["incident_description"] ?? ""; ?>

					</div>
				</div>
				<h5 class="mt-3 required">Footage Upload</h5>
				<div class="row">
					<div class="form-group col">
						<div class="alert alert-info w-50 mb-0">
							<h6>Requirements:</h6>
							<div>
								Max upload size:
								<?php
								echo MAX_UPLOAD_FILE_SIZE ."MB";
								echo " (". MAX_UPLOAD_FILE_SIZE / 1024 ."GB)"
								?>
							</div>
							<div class="mt-2">
								Allowed file types:
								<?php echo implode(", ", ALLOWED_FILE_TYPES); ?>
							</div>
						</div>

						<div class="custom-file my-4">
							<label for="footage_upload" class="custom-file-label">Upload your footage</label>
							<input type="file" name="footage_upload" id="footage_upload" class="form-control custom-file-input">
						</div>

						<small class="alert alert-primary d-block">Note: Your footage won't be uploaded until the form has successfully been submitted.</small>

						<?php echo $errors["footage_upload"] ?? ""; ?>
					</div>
				</div>
				
				<div class="row mt-4">
					<div class="form-group col">
						<label for="camera_fitting" class="required">How is the camera fitted?</label>
						<select name="report[camera_fitting]" id="camera_fitting" class="custom-select">
							<option selected value="">Select a fitting type</option>
							<?php
							$fitting_val = $postback_value["camera_fitting"] ?? "";

							if ($fittings) {
								foreach ($fittings as $fitting) {?>
										<option value="<?php echo $fitting["id"]; ?>" <?php echo is_selected($fitting["id"], $fitting_val); ?>><?php echo $fitting["name"]; ?></option>
								<?php }
							}
							?>
						</select>
						<?php echo $errors["camera_fitting"] ?? ""; ?>

					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<p>Is the date/time displayed on the footage different to which you stated above?</p>
						
						<?php $switch_val = $postback_value["footage_datetime_difference_switch"] ?? ""; ?>

						<input type="checkbox" data-toggle="toggle" data-size="sm" data-on="Yes" data-off="No" id="footage_datetime_difference_switch" name="report[footage_datetime_difference_switch]" <?php echo is_checked("on", $switch_val); ?>>
					</div>
					<div class="form-group col">
						<div id="footage_datetime_different_textarea" class="d-none">
							<label for="footage_datetime_difference_reason" class="required">Please explain why there is a difference in the date/time stated above and the timestamp on your video. This information is required for evidence continuity purposes</label>
							<textarea name="report[footage_datetime_difference_reason]" id="footage_datetime_difference_reason" cols="30" rows="10" class="form-control"><?php echo $postback_value["footage_datetime_difference_reason"] ?? "" ?></textarea>
							<?php echo $errors["footage_datetime_difference_reason"] ?? ""; ?>

						</div>
					</div>
				</div>

				<h5 class="mt-5">Declaration</h5>
				<p>Before you are able to submit a report, please read the following and confirm your agreement.</p>
				<div class="row mt-5">
					<div class="form-group col">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="attend_court_confirmation" name="report[attend_court_confirmation]">
							<label class="form-check-label required" for="attend_court_confirmation">
							By submitting a report, you understand that you may be required to attend court in order to confirm the information given in your statement. The police will be able to support you through this process. Please note that not all cases will be heard in court.<br><br>
							I understand and am willing to attend Court and give evidence in relation to this matter.
							</label>
							<?php echo $errors["attend_court_confirmation"] ?? ""; ?>

						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="form-group col">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="statement_retention_confirmation" name="report[statement_retention_confirmation]">
							<label class="form-check-label required" for="statement_retention_confirmation">
							All statements associated with an offence or prosecution will be retained in line with the police forces retention policies.
							</label>
							<?php echo $errors["statement_retention_confirmation"] ?? ""; ?>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="casualty_prevention_confirmation" name="report[casualty_prevention_confirmation]">
							<label class="form-check-label required" for="casualty_prevention_confirmation">
							I am happy for this statement and digital imagery/footage to be disclosed as part of the Criminal Justice process and for casualty prevention purposes.
							</label>
							<?php echo $errors["casualty_prevention_confirmation"] ?? ""; ?>

						</div>
					</div>
				</div>
				<div class="row mt-5">
					<div class="form-group col">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="statement_true_confirmation" name="report[statement_true_confirmation]">
							<label class="form-check-label required" for="statement_true_confirmation">
							I confirm this statement is true to the best of my knowledge and belief and I make it knowing that if it is tendered in evidence I shall be liable to prosecution if I have wilfully stated in it anything which I know to be false, or do not believe to be true.
							</label>
							<?php echo $errors["statement_true_confirmation"] ?? ""; ?>

						</div>
					
					</div>
				</div>
				<div class="row">
					<button type="submit" class="btn btn-primary" name="report[submit]">Submit</button>
				</div>
			</div>
		</form>
	</div>


</section>
