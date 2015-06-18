<?php
/**
Template Name: Reserve
**/

if(current_user_can('reserve'))
{
	$event = get_post_meta(get_the_ID(), 'id', true);
?>

<link rel="stylesheet" href="wp-content/themes/theme/style.css">
<link rel="stylesheet" href="wp-content/themes/theme/css/reserve.css">
<link rel="stylesheet" href="jquery-ui.css">

<script src="jquery-1.10.2.js"></script>
<script src="jquery-ui.js"></script>

<script language="JavaScript" type="text/javascript">
	function linkasbutton ( selectedtype )
	{
		document.tableform.number.value = selectedtype ;
	}

	function linkasbutton2 (  selectedtype )
	{
		document.tableform.color.value = selectedtype ;
		document.tableform.submit() ;
	}
</script>

<?php
$counttime = 20;

$page = $_SERVER['REQUEST_URI'];

$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , 'full');

$usercurrent = $current_user->ID;

$querytables = 'SELECT * FROM tables WHERE event_id = '.$event.' ORDER BY id';
				
$queryreservations = "SELECT * FROM wp_em_bookings where reserve <> ''";
					  
$queryuserdata = 'SELECT ID, user_login 
				  FROM wp_users 
				  ORDER BY ID';
				  
$outputqueryuserdata = mysql_query($queryuserdata);

$outputquerytables = mysql_query($querytables);

$outputqueryreservations = mysql_query($queryreservations);

while($userdata = mysql_fetch_array($outputqueryuserdata))
{
	$ids[$userdata['user_login']] = $userdata['ID'];
	$user[$userdata['ID']] = $userdata['user_login'];
}

$count2 = 0;

while($outputreservations = mysql_fetch_array($outputqueryreservations))
{
	$count2 = $count2 + 1;
	$places[$count2] = substr($outputreservations['reserve'],1);
	$idsreserved[$count2] = $outputreservations['person_id'];
	$users[$places[$count2]] = $user[$outputreservations['person_id']];
	$usertbnr[$outputreservations['person_id']] = $places[$count2];
}

if (isset($_POST['search']))
{
	$searcheduser = $ids[$_POST['search']];
}

$places = array_values(array_filter($places));

echo '<meta http-equiv="refresh" content="' .$counttime. ';URL=' . $_SERVER['REQUEST_URI'] . '">';

if (isset($_POST['number']))
{
	switch($_POST['color'])
	{
		case 1:
			$colorvalue = 'B';
			break;
		case 2:
			$colorvalue = 'R';
			break;
		case 3:
			$colorvalue = 'O';
			break;
		case 4:
			$colorvalue = 'G';
			break;
		case 5:
			$colorvalue = 'P';	
			break;
	}
	
	$tablenrcolor = $colorvalue.$_POST['number'];
	
	$qinsert = 'UPDATE wp_em_bookings 
				SET reserve = "' . $tablenrcolor . '"
				WHERE person_id = ' . $usercurrent . '
				AND event_id = ' . $event . '';
	
	mysql_query($qinsert);
	
	$_SESSION['reserved'] = 1;
	
	header("Location: ". $_SERVER['REQUEST_URI']);
}
?>

<div class="background">
	<img src="<?php echo $imgsrc[0]; ?>" style="width: 1007px; height: 600px;">
</div>

<?php
if ($_SESSION['reserved'] >= 1)
{
	$_SESSION['reserved'] = $_SESSION['reserved'] + 1;
}

$count = 0;

while($outputtables = mysql_fetch_array($outputquerytables))
{
	$count = $count + 1;
	$tablewidth[$count] = $outputtables['width'];
	$tableheight[$count] = $outputtables['height'];
	$tableY[$count] = $outputtables['y'];
	$tableX[$count] = $outputtables['x'];
	$rows[$count] = $outputtables['rows'];
	$columns[$count] = $outputtables['columns'];
	$colnum[$count] = $outputtables['color'];
	$degrees[$count] = $outputtables['degrees'];
	$permissions[$count] = $outputtables['permissions'];

	$tds = $rows[$count] * $columns[$count];
	$tdnumber = $tdnumber + $tds;
	$tdnumber2 = $tdnumber;
}

$tdnumber = $tdnumber + 1;		

$tables = count($tablewidth);

$blue 	= '#284482';
$red 	= '#EA2B1F';
$orange = '#FF7F27';
$yellow	= '#F4E76E';
$purple = '#8F3B8F';

echo '<form name="tableform" method="post" action="' . $_SERVER['REQUEST_URI'] . '">';
echo '<input type="hidden" name="number" id="number"></input>';
echo '<input type="hidden" name="color" id="color"></input>';

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
	<div style="
	position: absolute; 
	top: ' . $tableY[$tables] . ';
	left: ' . $tableX[$tables] . ';
	background-color: #FFFFFF;
		-webkit-transform: rotate(' . $degrees[$tables] . 'deg); /* Safari and Chrome */
		-moz-transform: rotate(' . $degrees[$tables] . 'deg);   /* Firefox 			 */
		-ms-transform: rotate(' . $degrees[$tables] . 'deg);   /* IE 9				*/
		-o-transform: rotate(' . $degrees[$tables] . 'deg);   /* Opera			   */
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
			
			if ($tdnumber == $usertbnr[$searcheduser])
			{
				echo '<td style="border: 2.5px solid ' . $color . ';" id="' . $tdnumber . '" align="center" class="blink_me"><a onmouseover="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Plaats " . $tdnumber . " is gereserveerd door " . $users[$tdnumber] . "</td></tr></table>'".';" onclick="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Plaats " . $tdnumber . " is gereserveerd door " . $users[$tdnumber] . "</td></tr></table>'".';">';
			}
			else
			{
				if (in_array($tdnumber, $places)) 
				{
					echo '<td style="border: 2.5px solid ' . $color . ';" class="reserved" id="' . $tdnumber . '" align="center"><a onmouseover="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Plaats " . $tdnumber . " is gereserveerd door " . $users[$tdnumber] . "</td></tr></table>'".';" onclick="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Plaats " . $tdnumber . " is gereserveerd door " . $users[$tdnumber] . "</td></tr></table>'".';">';
				}
				else
				{
					if ( current_user_can($permissions[$tables]) )
					{
						echo '<td style="border: 2.5px solid ' . $color . ';" class="clickable" id="' . $tdnumber . '" align="center" class="clickable"><a onclick="javascript:linkasbutton('."'" . $tdnumber . "'".'); linkasbutton2('."'" . $colnum[$tables] . "'".');" onmouseover="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Plaats " . $tdnumber . " is vrij</td></tr></table>'".';" onclick="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Plaats " . $tdnumber . " is vrij</td></tr></table>'".';">';
					}
					else
					{
						echo '<td style="border: 2.5px solid ' . $color . ';" class="nopermission" id="' . $tdnumber . '" align="center" class="nopermission"><a onmouseover="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Onvoldoende rechten voor plaats " . $tdnumber . "</td></tr></table>'".';" onclick="javascript:document.getElementById('."'"."namediv"."'".').innerHTML = '."'<table><tr><td>Onvoldoende rechten voor plaats " . $tdnumber . "</td></tr></table>'".';">';
					}
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
?>

<script>
  $(function() {
    var availableTags = [
		<?php
		$count3 = 0;
		
		while($count3 <= $tdnumber2)
		{
			if (in_array($count3, $places)) 
			{
				echo '"' . $users[$count3] . '",';
			}
			
			$count3 = $count3 + 1;
		}
		?>
    ];
    $( "#search" ).autocomplete({
      source: availableTags
    });
  });
</script>

</form>

<div class="namediv" id="namediv">
<?php
if ($_SESSION['reserved'] >= 1)
{
	echo '<table><tr><td>Plaats gereserveerd</td></tr></table>';
}

if ($_SESSION['reserved'] == 3)
{
	$_SESSION['reserved'] = 0;
}
?>
</div>

<div class="searchdiv" id="searchdiv">
	<form name="searchform" method="post" action="<?php $page; ?>">
	<input type="text" name="search" id="search">
	<input type="submit" value="Zoek deelnemer" class="button">
	</form>
</div>

<?php
}
else 
{ 
	echo 'Je hebt onvoldoende rechten om deze pagina te kunnen bekijken.';
}
?>
