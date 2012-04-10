<?php
header("Content-type: text/css"); 

$width = '48%';

if ( $_GET['genesisopt_width']  && $_GET['genesisopt_no_pair'] == 0 && $_GET['genesisopt_enable_custom_styles'] == 1  ) {
$width = $_GET['genesisopt_width'];
$width .= "px";
}
if ( $_GET['genesisopt_height'] == '' || $_GET['genesisopt_enable_custom_styles'] != 1 ) {
	$height = "auto";
} else {
$height = $_GET['genesisopt_height'];
$height .= "px";
}

if ( $_GET['genesisopt_no_pair'] == 1 ) { ?>

#post-teasers, .post-teasers-pair {
	clear:both;
}
#post-teasers .genesis-grid-even,
#post-teasers .genesis-grid-odd {
	width:100%;
	float: none;
	height: auto;
}

<?php 
} else {
?>

#post-teasers, .post-teasers-pair {
	clear:both;
}
#post-teasers .post-teasers-pair .genesis-grid-odd {
	width:<?php echo $width ?>;
	height:<?php echo $height ?>;
	float: left;
}
#post-teasers .post-teasers-pair .genesis-grid-even {
	width:<?php echo $width ?>;
	height:<?php echo $height ?>;
	float: right;
}

<?php }