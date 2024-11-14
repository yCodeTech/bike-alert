import PoliceForce from "./PoliceForce_class.js";

console.log("Test Reference: 2YU74fQwO5");

$(document).ready(function () {
	/**
	 * Custom File Input plugin to display file name on the input
	 * https://github.com/Johann-S/bs-custom-file-input
	 */
	bsCustomFileInput.init(".custom-file-input");

	/**
	 * Display a textarea if the switch is in the "on" state (checked).
	 */
	$(".toggle #footage_datetime_difference_switch").on("change", function () {
		const $textarea = $("#footage_datetime_different_textarea");
		if ($(this).is(":checked")) {
			$textarea.removeClass("d-none");
		} else {
			$textarea.addClass("d-none");
		}
	});

	// For the postback to work.
	$(".toggle #footage_datetime_difference_switch[data-checked='true']").bootstrapToggle("toggle");

	// Police force by location
	$("input#incident_county, input#incident_location").on("focusout", function (e) {
		const rd_city_val = $("input#incident_location").val();
		const county_val = $("input#incident_county").val();
		const $police_force_output = $("#police_force_output");

		$police_force_output.parent().find(".error").remove();

		if (rd_city_val != "" && county_val != "") {
			$police_force_output.val("Searching for Local Police Force");

			const policeForce = new PoliceForce(rd_city_val, county_val).get_police_force();

			policeForce.then(function (forceDetails) {
				console.log(forceDetails);

				if (forceDetails === "error") {
					const msg = "Can't find a police force. Please make sure the location is correct.";
					const $error = `<div class='alert alert-danger error mt-3' role='alert'>${msg}</div>`;
					$police_force_output.after($error);
				}

				const name = forceDetails.name;

				$police_force_output.val(name);
			});
		}
	});
});
