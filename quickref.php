<?php
require_once 'prepend.inc';

$NUMACROSS=2;
$SHOW_CLOSE=10;

if (!isset($lang)) $lang = default_language();
if (!file_exists("$DOCUMENT_ROOT/manual/$lang")) $lang = "en";

function makeTable($lang,$array) {
	global $NUMACROSS;

	echo '<TABLE BORDER="0" CELLPADDING="5" CELLSPACING="0" WIDTH="580">';
	echo '<TR VALIGN="top"><TD WIDTH="50%">';
	$i=0;
	$limit = ceil(count($array)/$NUMACROSS);
	asort($array);
	while (list($file,$name)=each($array)) {
		if ($i>0 && $i%$limit==0) {
			echo "</TD><TD WIDTH=\"50%\">\n";
		}
		echo "<A HREF=\"/manual/".$lang."/".$file."\">".$name."</A><BR>\n";
		$i++;
	}
	echo '</TD></TR></TABLE>';
}



$d = dir("$DOCUMENT_ROOT/manual/en");
$functions = $maybe = $temp = array();
$p = 0;

while($entry=$d->read()) {
	if (substr($entry, 0, 1) == ".") {
		continue;
	}
	if (ereg('(function|class)\.(.+)\.php',$entry,$x)) {
		$funcname = str_replace('-', '_', $x[2]);
		$functions[$entry] = $funcname;

		if (function_exists('similar_text') && $notfound) {
			similar_text($funcname, $notfound, &$p); 
			$temp[$entry] = $p;
		}

	}
}
$d->close();
arsort($temp);

$i = 0;
while (list($file,$p)=each($temp)) {
	$funcname = $functions[$file];
	$maybe[$file] = $funcname;
	if ($p>=70 || stristr($funcname,$notfound)) {
		$maybe[$file] = '<b>' . $functions[$file] . '</b>';
	}
	if ($i++ > $SHOW_CLOSE) {
		break;
	}
}


commonHeader("PHP Manual Quick Reference");
?>

<h1>PHP Function List</h1>

<?php if ($notfound) { ?>

<P>
Sorry, but the function <B><?php echo $notfound; ?></B> is not in the online manual.
Perhaps you misspelled it, or it is a relatively new function that hasn't made it 
into the online documentation yet.  The following are the <?php echo $SHOW_CLOSE;?> 
functions which seem to be closest in spelling to <B><?php echo $notfound;?></B> (really
good matches are in bold).  Perhaps you were looking for one of these:
</P>

<?php makeTable($lang,$maybe); ?>

<P>
If you want to search the entire PHP website for the string &quot;<B><?php echo $notfound; ?></B>&quot;, 
then <?php print_link('search.php?show=nosource&pattern='.urlencode($notfound), 'click here'); ?>.
</P>

<p>
For a quick overview over all PHP functions, 
<?php print_link('quickref.php', 'click here') ?>.
</p>
<?php
  commonFooter();
  exit;
} 
?>

<P>
Here is a list of all the PHP functions.  Click on any one of them to jump to that page in the manual.
</P>

<?php makeTable($lang,$functions); ?>

</TD></TR>
</TABLE>

<?php commonFooter(); ?>
