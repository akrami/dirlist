<?php
function retrieve($dir, $indent = 0) {
	$dirlist = array();
	$list = array_diff(scandir($dir), array('..', '.'));
	if (!$list) {
		return false;
	}
	foreach ($list as $item) {
		if(filetype($dir.'/'.$item) == 'file'){
			$file_type = mime_content_type($dir.'/'.$item);
		}else{
			$file_type = 'dir';
		}
		$file_size = filesize($dir.'/'.$item);
		array_push($dirlist, array('name' => $item, 'indent' => $indent, 'type' => $file_type, 'size' => $file_size));
		if (is_dir($dir.'/'.$item)) {
			$temp = retrieve($dir.'/'.$item, $indent+1);
			if ($temp) {
				$dirlist = array_merge($dirlist, $temp);
			}
		}
	}
	return $dirlist;
}

function convertByte($size){
	if ($size==0) {
		return 0;
	}
  $base = log($size) / log(1024);
  $suffix = array("", "KB", "MB", "GB", "TB");
  $f_base = floor($base);
  return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

$result = retrieve('.');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Directory List</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
	<style type="text/css" media="screen">
		* {
			font-family: 'Open Sans Condensed', sans-serif;
		}
		table td {
			font-size: 0.9em;
			padding: 5px 3px;
		}
		table td i {
			font-style: normal;
			color: #E0E0E0;
		}
		table {
			width: 100%;
		}
		table tbody tr:nth-child(even) {
			background-color: #FAFAFA;
		}
		table thead th {
			background-color: #F0F0F0;
			font-size: 1.5em;
			font-weight: bolder;
			text-align: left;
			padding: 5px 3px 15px 5px;
			color: white;
			text-shadow: 1px 1px 1px #777;
		}
		.fa-folder-open {
			color: #EAD38F;
		}
		.fa-file-alt {
			color: #A3A3A3;
		}
		.fa-file-audio {
			color: #B76E79;
		}
		.fa-file-video {
			color: #C4D4E0;
		}
		.fa-file-image {
			color: #FA929E;
		}
	</style>
</head>
<body>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>File</th>
				<th>Type</th>
				<th>Size</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($result as $file) { ?>
				<tr>
					<td><?php
					if ($file['indent']>0) {
						for ($i=0; $i < $file['indent']; $i++) { 
							echo "&emsp;";
						}
					}
					if ($file['type'] == 'dir') {
						echo '<i class="fas fa-folder-open"></i> ';
					} elseif (strpos($file['type'], 'audio') !== false) {
						echo '<i class="fas fa-file-audio"></i> ';
					} elseif (strpos($file['type'], 'image') !== false) {
						echo '<i class="fas fa-file-image"></i> ';
					} elseif (strpos($file['type'], 'video') !== false) {
						echo '<i class="fas fa-file-video"></i> ';
					} elseif (strpos($file['type'], 'pdf') !== false) {
						echo '<i class="fas fa-file-pdf"></i> ';
					} elseif (strpos($file['type'], 'text') !== false) {
						echo '<i class="fas fa-file-alt"></i> ';
					} elseif (strpos($file['type'], 'archive') !== false || strpos($file['type'], 'zip') !== false || strpos($file['type'], 'rar') !== false || strpos($file['type'], 'tar') !== false || strpos($file['type'], '7z') !== false) {
						echo '<i class="fas fa-file-archive"></i> ';
					} else {
						echo '<i class="fas fa-file"></i> ';
					}
					echo $file['name'] ?></td>
					<td><?php echo $file['type'] ?></td>
					<td><?php echo convertByte($file['size']) ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>