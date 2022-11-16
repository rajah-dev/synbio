<?php
require_once '../includes/common.php';
session_start();

$tableName = "teamprojects";

function highlightKeywords($text, $keyword) {
	$wordsAry = explode(" ", $keyword);
	$wordsCount = count($wordsAry);
		
	for($i=0;$i<$wordsCount;$i++) {
		$highlighted_text = "<span style='font-weight:bold;'>$wordsAry[$i]</span>";
		$text = str_ireplace($wordsAry[$i], $highlighted_text, $text);
	}

	return $text;
}

// if search button pressed
if (isset($_POST["submitSearch"])) {

	$searchText = htmlspecialchars($_POST['searchText']); //grab searchText and strip of special characters
	$keywords = explode(" ", $searchText);
	

	if (isset($_POST["filter_year"])) { 
		$filterYear = ["year", $_POST["filter_year"]]; //grab filter by year
	} else { $filterYear = FALSE; } 
	if (isset($_POST["filter_track"])) { 
		$filterTrack = ["track", $_POST["filter_track"]]; //grab filter by track
	} else { $filterTrack = FALSE; } 
	if (isset($_POST["filter_section"])) { 
		$filterSection = ["section", $_POST["filter_section"]]; //grab filter by section
	} else { $filterSection = FALSE; } 
	if (isset($_POST["filter_medal"])) { 
		$filterMedal = ["medal", $_POST["filter_medal"]]; //grab filter by medal
	} else { $filterMedal = FALSE; } 
	$allFilters = [
		$filterYear,
		$filterTrack,
		$filterSection,
		$filterMedal];


	//$fullRequest = $queryDB->generateSimpleRequest();
	$results = $queryDB->selectFrom($tableName, $searchText, $allFilters);
}

?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>project search tool</title>
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
	    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
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
		ul.simpleCol3 {
			columns: 3;
			width: 100%;
		}
		ul.simpleCol2 {
			columns: 2;
			width: 100%;
		}


	</style>
	<?php require_once '../includes/header.php'; ?>

	<body>
		<div class="section">
		<div class="columns">
			<div class="column is-one-fifth-fullhd is-one-quarter-widescreen is-one-third-desktop is-one-third-tablet">
				<nav class="panel is-primary">
					<p class="panel-heading">search & filter</p>

				<form method="POST">
					<div class="panel-block">
						<div class="control is-expanded">
							<input class="input" type="search" name="searchText" placeholder="Search" aria-label="Search"
								value="<?= isset($_POST['searchText']) ? htmlentities($_POST['searchText']) : "" ?>"
							/>
						</div>
					</div>
					<div class="panel-block"><h6 class="title is-6">Year</h5></div>
					<div class="panel-block">
						<ul class="simpleCol3" style="max-height: 160px;">
						<?php $searchOptions->populateFilterMenu($tableName, 'year') 

						?>
						</ul>
					</div>
					<div class="panel-block"><h6 class="title is-6">Track</h5></div>
					<div class="panel-block">
						<ul>
						<?php $searchOptions->populateFilterMenu($tableName, 'track') 

						?>
						</ul>
					</div>
					<div class="panel-block"><h6 class="title is-6">Section</h5></div>
					<div class="panel-block">
						<ul>
						<?php $searchOptions->populateFilterMenu($tableName, 'section') 

						?>
						</ul>
					</div>
					<div class="panel-block"><h6 class="title is-6">Medal</h5></div>					
					<div class="panel-block">
						<ul class="simpleCol2" style="max-height: 50px;">
						<?php $searchOptions->populateFilterMenu($tableName, 'medal') 

						?>
						</ul>
					</div>
					<div class="panel-block">
						<button class="button is-info is-fullwidth" name="submitSearch" type="submit">
						Search
						</button>
					</div>

				</form>

			</nav>
			</div>
			<div class="column">
				<table class="table is-fullwidth is-hoverable">
					<thead>
						<th>fav</th>
						<th>team info</th>
						<th>track</th>
						<th>title & abstract</th>
						<th>medal</th>
					</thead>
					<?php if(isset($_POST["submitSearch"])) : ?>
					<?php foreach ($results as $project) : ?>
					<tr>
						<td><button>&#9734;</button></td>
						<td>
							<?php echo "<a href='{$project->wiki}' target='_blank'>{$project->team_name}</a>"?></br>
							<?= $project->year ?></br>
							<?= $project->section ?>
						</td>
						<td><?= $project->track ?></td>
						<td>
							<h4 class="title is-4"><?= htmlspecialchars($project->project_title); ?></h4>
							<?= htmlspecialchars($project->abstract); ?></td>
						<td><?= $project->medal ?></td>
					</tr>
					<?php endforeach ?>
					<?php endif ?>
				</table>
			</div>
		</div>
		</div>


          

	</body>
</html>