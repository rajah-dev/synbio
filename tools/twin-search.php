<?php
require_once '../includes/common.php';
session_start();

$tableName = "parts";

// if search button pressed

if (isset($_POST["submitSearch"])) {

	$searchText = htmlspecialchars($_POST['searchText']); //grab searchText and strip of special characters

	//$fullRequest = $queryProject->generateSimpleRequest();
	$results = $queryDB->findTwins($tableName, $searchText);
}

?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>part search tool</title>
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
	    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	</head>
	<style type="text/css">
		ul {
			list-style: none;
			display:block;
		}
		/*ul.flexCol {
			display: flex;
			flex-wrap: wrap;
			flex-direction: column;
		}*/
		ul.simpleCol {
			columns: 2;
		}

	</style>
	<?php require_once '../includes/header.php'; ?>

	<body>
		<div class="section">
		<div class="columns">
			<div class="column is-one-fifth-fullhd is-one-quarter-widescreen is-one-third-desktop is-one-third-tablet">
				<nav class="panel is-primary">
					<p class="panel-heading">search for twins</p>

				<form method="POST">
					<div class="panel-block">
						<div class="control is-expanded">
							<input class="input" type="search" name="searchText" placeholder="Search" aria-label="Search"
								value="<?= isset($_POST['searchText']) ? htmlentities($_POST['searchText']) : "" ?>"
							/>
						</div>
					</div>
					<div class="panel-block">
						<button class="button is-info is-fullwidth" name="submitSearch" type="submit">
						Search
						</button>
					</div>

				</form>

				<div class="field">
					<ul>

					</ul>
				</div>
			</nav>
			</div>
			<div class="column">
				<div class="container">
					<?php if(isset($_POST["submitSearch"])) : ?>
						<?= $searchText ?>
						<?= sha1($searchText, true) ?>
						<?= count($results) ?>
						<?= $queryDB->fullMYSQLquery ?>
					<?php endif ?>
				</div>
				<table class="table is-fullwidth is-hoverable">
					<thead>
						<th>save</th>
						<th>part name</th>
						<th>short desc</th>
						<th>part type</th>
						<th class="is-narrow">bp</th>
						<th class="is-narrow">doc size</th>
						<th class="is-narrow">uses</th>
						<th>categories</th>
						<th class="is-narrow">status</th>
					</thead>
					<?php if(isset($_POST["submitSearch"])) : ?>
					<?php foreach ($results as $part) : ?>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" name="saveparts[]" value="<?= $part->part_id ?>">
							</label>							
						</td>
						<td><?= $part->part_name ?></td>
						<td><?= htmlspecialchars($part->short_desc) ?></td>
						<td><?= $part->composition . '</br>' . $part->part_type ?></td>
						<td><?= $part->sequence_length ?> bp</td>
						<td><?= $part->doc_size ?></td>
						<td><?= $part->uses ?></td>
						<td><?= $part->categories ?></td>
						<td><?= $part->status . '</br>' .  $part->sample_status ?></td>					
					</tr>
					<?php endforeach ?>
					<?php endif ?>
				</table>
			</div>
		</div>
		</div>


          

	</body>
</html>