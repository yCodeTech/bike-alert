export default class PoliceForce {
	rd_city_val;
	county_val;
	lat;
	lon;
	// OLD code. Removed after graduated uni in favour of new secure code.
	/* #api_key = "*******";

	url_params; */

	constructor(rd_city_val, county_val) {
		console.info(
			"%cDev caveat: The free Geocode API at https://geocode.maps.co/ is restricted to 1 request/second, and 1 Million requests/month",
			"color: orange;"
		);

		this.rd_city_val = rd_city_val;
		this.county_val = county_val;

		// OLD code. Removed after graduated uni in favour of new secure code.
		/* this.obj = {
			q: `${this.rd_city_val}+${this.county_val}+UK`,
			api_key: this.#api_key,
		};

		this.url_params = this.#convert_object_to_url_params(this.obj); */
	}

	// OLD code. Removed after graduated uni in favour of new secure code.

	/**
	 * Convert an object to a URL query params.
	 *
	 * Code from https://shivrajan.medium.com/how-to-convert-an-objects-key-value-pairs-to-url-parameters-in-javascript-f82d0bfc4b3d
	 *
	 * @private
	 *
	 * @param {object} obj The object to convert to URL query string
	 * @returns {string} The query params
	 */
	/* #convert_object_to_url_params(obj) {
		const params = [];
		for (const key in obj) {
			params.push(`${encodeURIComponent(key)}=${encodeURIComponent(obj[key])}`);
		}
		return params.join("&");
	} */

	/**
	 * Get the territorial police force details.
	 * Uses the free police API from https://data.police.uk/docs/
	 *
	 * @public
	 *
	 * @returns {Promise} An object in the Promise.
	 */
	async get_police_force() {
		// Set the keyword this to a variable so the reference to the class
		// doesn't get lost in the promises.
		const self = this;
		return this.#get_coords().then(function ([lat, lon]) {
			return self.#locate_police_force(lat, lon).then(function ({force}) {
				return self.load_data(`https://data.police.uk/api/forces/${force}`);
			});
		});
	}

	/**
	 * Convert location address to latitude and longitude coordinates.
	 *
	 * Uses the free Geocode API from https://geocode.maps.co/
	 * Caveat: 1 request/second, and 1 Million requests/month
	 *
	 * @private
	 *
	 * @returns {Promise} An array of lat and lon in the Promise.
	 */
	/* async */ #get_coords() {
		// OLD code. Removed after graduated uni in favour of new secure code.

		/* const data = await this.load_data(`https://geocode.maps.co/search?${this.url_params}`);

		// If the data array is empty, then just return it,
		// so we can error it out.
		if (data.length === 0) {
			return data;
		}

		return [data[0].lat, data[0].lon]; */

		// Get the Geocode coords from the server.
		// Added after graduated uni to prevent leaking of the api key in JS as it was previously.
		return this.load_data("/ajax", "json", {
			method: "POST",
			body: `action=get_geocode_coords&rd_city_val=${this.rd_city_val}&county_val=${this.county_val}`,
			headers: {
				Accept: "application/json",
				"Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
			},
		})
			.then((data) => {
				console.log(data);
				// If the data array is empty, then just return it,
				// so we can error it out.
				if (data.length === 0) {
					return data;
				}
				return [data[0].lat, data[0].lon];
			})
			.catch((err) => {
				console.log(err);
			});
	}

	/**
	 * Locate the territorial police force name by lat and lon coords.
	 * Uses the free police API from https://data.police.uk/docs/
	 *
	 * @private
	 *
	 * @param {string} lat Latitude
	 * @param {string} lon Longitude
	 * @returns {Promise} An object in the Promise.
	 */
	async #locate_police_force(lat, lon) {
		return await this.load_data(`https://data.police.uk/api/locate-neighbourhood?q=${lat},${lon}`);
	}

	/**
	 * Get data from an API
	 *
	 * Changed after graduated uni.
	 *
	 * @public
	 *
	 * @param {string} url
	 */
	async load_data(url, type = "json", $args = {}) {
		let response = await fetch(url, $args);

		let json;

		if ((await response.status) === 200) {
			if (type === "json") {
				json = response.json();
			} else {
				json = response.text();
			}
		} else {
			json = "error";
		}

		return json;
	}
}
