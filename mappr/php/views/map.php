<div id="opts">
	<select id="mobile_country_code">
		<option value='0'>All</option>
		<?php
		$r = $db_connection->query("SELECT DISTINCT(mnc), mcc FROM " . DB_SECTORS);
		while ($d = $r->fetch_object()) {
			echo "<option value='{$d->mnc}'>{$d->mcc}-{$d->mnc}</option>";
		}
		?>
	</select>

	<input type="search" name="enb_search" id="enb_search" placeholder="Search eNBs" />
	<button type="button" aria-label="Do eNodeB Search" class="btn btn-primary" id="enb_search_submit"><i class="fas fa-search"></i></button>

	<button type="button" aria-label="Toggle sidebar" class="btn btn-secondary" id="sidebar_toggle"><i class="fas fa-bars"></i></button>
	<button type="button" aria-label="Move map to current location" class="btn btn-primary" id="locate_user_manual"><i class="fas fa-location-arrow"></i></button>
	<button type="button" aria-label="Advanced search" class="btn btn-primary" data-toggle="modal" data-target="#searchPopup"><i class="fas fa-caret-square-down"></i></button>
</div>

<!-- Settings -->
<div class="modal fade" id="searchPopup" tabindex="-1" role="dialog" aria-labelledby="searchPopupLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="searchPopupLabel">Advanced Search</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<fieldset>
					<legend>Find a specific site...</legend>
					<input type="number" placeholder="Lower eNB ID*" name="adv_enb_lower_bound" id="adv_enb_lower_bound" />
					<input type="number" placeholder="Upper eNB ID*" name="adv_enb_upper_bound" id="adv_enb_upper_bound" />
					* Set both in order for this to take effect...
					<h5> - or - </h5>
					<input type="number" placeholder="eNodeB ID" name="adv_enb_specific" id="adv_enb_specific" />
				</fieldset>

				<fieldset>
					<legend>Map Settings</legend>
					<label for="adv_load_only_bounds_ne">Constrain NE</label>
					<input type="checkbox" name="adv_load_only_bounds_ne" id="adv_load_only_bounds_ne" checked />
					<label for="adv_load_only_bounds_sw">Constrain SW</label>
					<input type="checkbox" name="adv_load_only_bounds_sw" id="adv_load_only_bounds_sw" checked />
				</fieldset>

				<fieldset>
					<legend>Data Quantity (Ignored for now)</legend>
					<input type="number" placeholder="Max eNBs to load (default:2500)" name="adv_max_num_enb" id="adv_max_num_enb" />
					<input type="number" placeholder="Max sector count to load (default:36)" name="adv_max_num_sec" id="adv_max_num_sec" />
				</fieldset>

				<fieldset>
					<legend>Sectors to show</legend>
					<label for="adv_load_only_bounds_sector">Constrain to current area</label>
					<input type="checkbox" name="adv_load_only_bounds_sector" id="adv_load_only_bounds_sector" checked />
					<div id="sector_list">
						Not live yet...
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="advanced_search">Search</button>
			</div>
		</div>
	</div>
</div>

<div id="page">
	<div id="map"></div>

	<div id="sidebar">
		<nav class="nav nav-pills nav-justified">
			<a class="nav-item nav-link active" href="#" role="tab" data-sidebartab="map_settings"><i class="fas fa-map-marked-alt"></i></a>
			<a class="nav-item nav-link" href="#" role="tab" data-sidebartab="results"><i class="fas fa-broadcast-tower"></i></a>
			<a class="nav-item nav-link" href="#" role="tab" data-sidebartab="user_pane"><i class="fas fa-user-alt"></i></a>
			<a class="nav-item nav-link" href="#" role="tab" data-sidebartab="bookmarks"><i class="fas fa-bookmark"></i></a>
			<a class="nav-item nav-link" href="#" role="tab" data-sidebartab="files"><i class="fas fa-file"></i></a>
		</nav>
		<div class="tab-content">
			<div class="tab-pane" id="map_settings">
				<h2>Map Settings</h2>

				<div class="btn-group" role="group" aria-label="Map item controls">
					<button aria-label="Pause / Unpause node loading" id="node_loading_pause" class="btn btn-primary btn-block btn-sm">
						<i class="fas fa-pause"></i> Pause Node Loading
					</button><br />
					<button aria-label="Disable / Enable node polygons" id="node_polygons_pause" class="btn btn-info btn-block btn-sm">
						<i class="fas fa-shapes"></i> Disable Node Polygons
					</button><br />
					<button aria-label="Clear map markers" id="node_markers_clear" class="btn btn-danger btn-block btn-sm">
						<i class="fas fa-trash"></i> Delete Map Markers
					</button>
				</div>

				<fieldset>
					<legend>Results Options</legend>
					<label for="adv_map_show_verified">Show Located Nodes</label>
					<input type="checkbox" name="adv_map_show_verified" id="adv_map_show_verified" checked="checked" />
					<label for="adv_map_show_mls">Show MLS Nodes</label>
					<input type="checkbox" name="adv_map_show_mls" id="adv_map_show_mls" checked="checked" />
				</fieldset>
				<fieldset class="px-2">
					<legend>Base Map</legend>
					<select id="map_name" class="custom-select mb-3">
						<option value='osm'>OpenStreetMap</option>
						<option value='otm'>OpenTopoMap</option>
						<option value='rdi' selected="selected">G Streets</option>
						<option value='arm'>G Streets Alt</option>
						<option value='hyb'>G Hybrid</option>
						<option value='ter'>G Terrain</option>
						<option value='rdo'>G Roads Only</option>
						<option value='tro'>G Terrain Only</option>
						<option value='sat'>G Sat Only</option>
					</select>
					<hr/>
					<label for="world_location_search">Search for a location...</label>
					<div class="input-group mb-3">
						<input class="form-control" type="text" maxlength="250" minlength="2" id="world_location_search" name="world_location_search" aria-label="Search for an address" placeholder="Enter an address, postcode, city.." />
						<div class="input-group-append">
							<button id="do_world_location_search" class="btn btn-secondary" type="button"><i class="fas fa-search"></i></button>
						</div>
					</div>
					<hr/>
				</fieldset>
				<fieldset id="operator_maps">
					<legend>Operator Maps</legend>
					<label for="operator_tile_opacity">Tile Opacity</label>
					<input type="range" id="operator_tile_opacity" max="100" value="50" min="1" />
				</fieldset>
			</div>
			<div class="tab-pane" id="results">
				<table>
					<thead>
						<tr>
							<th>MNC</th>
							<th>eNB</th>
							<th>Sectors</th>
						</tr>
					</thead>
					<tbody id="results_tbl">
						<tr>
							<td colspan="3">No data</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="user_pane">
				<h2>User Settings</h2>
				<span>Nothing here yet</span>
			</div>
			<div class="tab-pane" id="bookmarks">
				<h2>Bookmarked Locations</h2>
				<button id="bookmarks_reload" class="btn btn-primary">Reload List</button>
			</div>
			<div class="tab-pane" id="files">
				<h2>CSV Files</h2>
				<input type="file" id="csv_import" accept="text/csv" />
				<table>
					<thead>
						<tr>
							<th>File Name</th>
							<th>Rows</th>
							<th>Options</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3">No files loaded</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="assets/js/util.js?v=<?php echo $fv; ?>"></script>
<script type="text/javascript" src="assets/js/map.js?v=<?php echo $fv; ?>" defer></script>