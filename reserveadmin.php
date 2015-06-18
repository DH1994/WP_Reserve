<?php
/**
Template Name: Reserveadmin
**/

if(current_user_can('reserve_admin'))
{
?>

<link rel="stylesheet" href="wp-content/themes/theme/style.css">
<link rel="stylesheet" href="wp-content/themes/theme/css/reserve.css">

<script src="jquery-1.10.2.js"></script>
<script src="jquery-ui.js"></script>
	
<?php
$outputevent = mysql_query('SELECT MAX( event_id ) FROM tables');
$maxevent = mysql_result($outputevent,0,0);

$querycountids = mysql_query('SELECT COUNT( id )FROM tables');
$countids = mysql_result($querycountids,0,0);

if ($maxevent == 0)
{
  $maxevent = 1;
}

if(isset($_GET["eventid"])) 
{
	$event = $_GET["eventid"];
}
elseif (isset($maxevent))
{
	$event = $maxevent;
}
else
{
	$event = 1;
}

$page = $_SERVER['REQUEST_URI'];

$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , 'full');

mysql_select_db('reservations');

$querytables = 'SELECT * FROM tables WHERE event_id = '.$event.' ORDER BY id';

$outputquerytables = mysql_query($querytables);

$count = 0;

while($outputtables = mysql_fetch_array($outputquerytables))
{
	$count = $count + 1;
	$ids[$count] = $outputtables['id'];
	$tablewidth[$count] = $outputtables['width'];
	$tableheight[$count] = $outputtables['height'];
	$tableY[$count] = $outputtables['y'];
	$tableX[$count] = $outputtables['x'];
	$rows[$count] = $outputtables['rows'];
	$columns[$count] = $outputtables['columns'];
	$colnum[$count] = $outputtables['color'];
	$degrees[$count] = $outputtables['degrees'];
	$permissions[$count] = '"'.$outputtables['permissions'].'"';
}

if (isset($_POST['table']))
{
	if (isset($_POST['update']))
	{
		$query = 'UPDATE tables SET	width = "' . $_POST['width'] . '",
									height = "' . $_POST['height'] . '",
									x = "' . $_POST['x'] . '",
									y = "' . $_POST['y'] . '",
									rows = "' . $_POST['rows'] . '",
									columns = "' . $_POST['columns'] . '",
									color = "' . $_POST['color'] . '",
									degrees = "' . $_POST['degrees'] . '",
									permissions = "' . $_POST['permissions'] . '",
									event_id = "' . $event . '"
				  WHERE id = ' . $_POST['table'] . '';
	}
	else
	{
		$query = 'INSERT INTO tables 
				  VALUES ("' . $_POST['table'] . '","210","50","10","10","2","5","1","0","reserve_",'.$event.')';
	}
	
	mysql_query($query);	
	header("Location: ". $_SERVER[$page]);
}

if (isset($_POST['delete']))
{
	$query = 'DELETE FROM tables 
			  WHERE id = ' . $_POST['delete'] . '';
			  
	echo $query;
	
	mysql_query($query);
	
	header("Location: ". $_SERVER['REQUEST_URI']);
}

?>

<div class="background">
	<img src="<?php echo $imgsrc[0]; ?>" style="width: 1007px; height: 600px;">
</div>

<script>
	$(document).ready(function(){
	$("#settings").draggable( {containment: "#background", scroll: false} );
	});
</script>

<?php
$tables = count($tablewidth);

while ($tables > 0)
{
	echo '
	<script>
		$(function() {
			$("#table'.$ids[$tables].'").draggable(
			{
				drag: function() {
				   var offset = $(this).offset();
				   var xPos = offset.left;
				   var yPos = offset.top;
				   document.settings.x.value = parseInt(xPos);
				   document.settings.y.value = parseInt(yPos);
				   document.settings.table.value = ' . $ids[$tables] . ';
				   document.settings.width.value = ' . $tablewidth[$tables] . ';
				   document.settings.height.value = ' . $tableheight[$tables] . ';
				   document.settings.rows.value = ' . $rows[$tables] . ';
				   document.settings.columns.value = ' . $columns[$tables] . ';
				   document.settings.color.value = ' . $colnum[$tables] . ';
				   document.settings.degrees.value = ' . $degrees[$tables] . ';
				   document.settings.permissions.value = ' . $permissions[$tables] . ';	
				   document.deleteform.delete.value = ' . $ids[$tables] . ';
				}
			});
		});
	</script>';
	
	$tds = $rows[$tables] * $columns[$tables];
	$tdnumber = $tdnumber + $tds;
	$tables = $tables - 1;
}

$tables = count($tablewidth);

echo '
		
	<script>
		function setform(selected) {
			var table = parseInt(selected.value);
				switch (table) {
				';
while ($tables > 0) {
	echo '
		case '.$ids[$tables].':
			document.settings.x.value = ' .$tableX[$tables] . ';
			document.settings.y.value = ' . $tableY[$tables] . ';
			document.settings.table.value = ' . $ids[$tables] . ';
			document.settings.width.value = ' . $tablewidth[$tables] . ';
			document.settings.height.value = ' . $tableheight[$tables] . ';
			document.settings.rows.value = ' . $rows[$tables] . ';
			document.settings.columns.value = ' . $columns[$tables] . ';
			document.settings.color.value = ' . $colnum[$tables] . ';
			document.settings.degrees.value = ' . $degrees[$tables] . ';
			document.settings.permissions.value = ' . $permissions[$tables] . ';	
			document.deleteform.delete.value = ' . $ids[$tables] . ';	
			break;
	';
	$tables = $tables - 1;
	}
						
echo '}} </script>';

$tdnumber = $tdnumber + 1;

$tables = count($tablewidth);
 
$blue 	= '#284482';
$red 	= '#EA2B1F';
$orange = '#FF7F27';
$yellow	= '#F4E76E';
$purple = '#8F3B8F';

$tables = count($tablewidth);

while($tables > 0)
{
	$columnstemp = $columns[$tables];
	$rowstemp = $rows[$tables];

	switch($colnum[$tables]) 
	{
		case 1:
			$color = $blue;
			break;
		case 2:
			$color = $red;
			break;
		case 3:
			$color = $orange;
			break;
		case 4:
			$color = $yellow;
			break;
		case 5:
			$color = $purple;
			break;
	}
	
	echo '
	<div class="table' . $tables . '" id="table' . $ids[$tables] . '" style="
	position: absolute; 
	top: ' . $tableY[$tables] . ';
	left: ' . $tableX[$tables] . ';
	background-color: #FFFFFF;
		-webkit-transform: rotate(' . $degrees[$tables] . 'deg); /* Safari and Chrome */
		-moz-transform: rotate(' . $degrees[$tables] . 'deg);   /* Firefox 	     */
		-ms-transform: rotate(' . $degrees[$tables] . 'deg);   /* IE 9	            */
		-o-transform: rotate(' . $degrees[$tables] . 'deg);   /* Opera		   */
		transform: rotate(' . $degrees[$tables] . 'deg);">
	';

	echo '<table style="border: 5px solid ' . $color . '; width: ' . $tablewidth[$tables] . '; height: ' . $tableheight[$tables] . ';">';

	while($rowstemp > 0) 
	{
		$rowstemp = $rowstemp - 1;
		
		echo '<tr>';
		
		while($columnstemp > 0)
		{
			$columnstemp = $columnstemp - 1;
			$tdnumber = $tdnumber - 1;

				if (in_array($tdnumber, $places)) 
				{
					echo '<td style="border: 2.5px solid ' . $color . ';" class="reserved" id="' . $tdnumber . '" align="center"><a>';
				}
				else
				{
					if ( current_user_can($permissions[$tables]) )
					{
						echo '<td style="border: 2.5px solid ' . $color . ';" class="clickable" id="' . $tdnumber . '" align="center" class="clickable"><a>';
					}
					else
					{
						echo '<td style="border: 2.5px solid ' . $color . ';" class="nopermission" id="' . $tdnumber . '" align="center" class="nopermission"><a>';
					}
				}
				
				echo $tdnumber . '</a>';
				echo '</td>';
		}
		
		$columnstemp = $columns[$tables];

		echo '</tr>';
	}

	echo '</table>';
	echo '</div>';

	$tables = $tables - 1;

	$rowstemp = $rows[$tables];
}	

$tables = count($tablewidth);
?>

</form>

<div class="settings" id="settings" draggable="true">
<table>
<form name="event" method="get" action="#">
		<tr><td>Event</td><td><select id="eventid" name="eventid" type="text">
					<?php	$events = 1;				
						while ($events <= $maxevent + 1) 
							{				
								echo '<option value="'.$events.'"'?> <?if ($events == $event) {echo 'selected="selected"';}?> <? echo '">'.$events.'</option>';
								$events = $events + 1;
							}
					?>
				  </select></td></tr>
	<tr><td></td><td><input type="submit" value="Change Event" class="button"></input></td></tr>	
</form>

<form name="settings" method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
	<tr><td></br></td></tr>	
	<tr><td>Vaknummer</td><td><select id="table" name="table" type="text" onclick="setform(this)" onchange="setform(this)">
					<?php						
						while ($tables > 0) 
							{				
								echo '<option value="'.$ids[$tables].'">'.$ids[$tables].'</option>';
								$tables = $tables - 1;
							}
					?>
				  </select></td></tr>
	<tr><td></br></td></tr>
	<tr><td>X-as positie</td><td><input id="x" name="x" type="text" ></input></td></tr>
	<tr><td>Y-as positie</td><td><input id="y" name="y" type="text" ></input></td></tr>
	<tr><td></br></td></tr>
	<tr><td>Breedte</td><td><input id="width" name="width" type="text"></input></td></tr>
	<tr><td>Hoogte</td><td><input id="height" name="height" type="text"></input></td></tr>
	<tr><td></br></td></tr>
	<tr><td>Rijen</td><td><input id="rows" name="rows" type="text"></input></td></tr>
	<tr><td>Kolommen</td><td><input id="columns" name="columns" type="text"></input></td></tr>
	<tr><td></br></td></tr>
	<tr><td>Kleur</td><td><select id="color" name="color" >
		<option value="1">Blauw</option>
		<option value="2">Rood</option>
		<option value="3">Oranje</option>
		<option value="4">Geel</option>
		<option value="5">Paars</option>
	</select></td></tr>
	<tr><td>Graden</td><td><input id="degrees" name="degrees" type="text"></input></td></tr>
	<tr><td>Rechten</td><td><input id="permissions" name="permissions" type="text"></input></td></tr>
	<tr><td></td><td style="font-size: 8px;">Deelnemer = reserve_deelnemer<br />V.I.P. = reserve_vip<br />Compo Admin = reserve_compoadmin<br />Stream Admin = reserve_streamadmin<br />Crewlid = reserve_crewlid</td></tr>
	<input id="update" name="update" type="hidden" value="update"></input>
	<tr><td></td><td><input type="submit" name="set" id="set" value="Opslaan" class="button"></input></td></tr>
</form>
<form method="post" action="<?php echo $page; ?>">
	<input id="table" name="table" type="text" hidden="true" value="<?php echo $countids + 1; ?>"></input>
	<tr><td></td><td><input type="submit" name="set" id="set" value="Nieuw vak" class="button"></input></td></tr>
</form>
<form name="deleteform" method="post" action="<?php echo $page; ?>">
	<input id="delete" name="delete" type="delete" hidden="true"></input>
	<tr><td></td><td><input type="submit" name="set" id="set" value="Verwijder vak" class="button"></input></td></tr>
</form>
</table>
</div>

<?php
}
else 
{ 
	echo 'Je hebt onvoldoende rechten om deze pagina te kunnen bekijken.';
}
?>
