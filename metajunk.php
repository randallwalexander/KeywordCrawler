<b>This will check for good keywords</B>
<br />
Complete URL including http://  Otherwise it will not work.
<br />
<form action="index.php" method="get">
URL: <input type="text" value="http://" size="50" name="url" />
<input type="submit" />
</form>
<br />
<b>This will check for stop words</B>
<br />
Complete URL including http://  Otherwise it will not work.
<br />
<form action="metajunk.php" method="get">
URL: <input type="text" value="http://" size="50" name="url" />
<input type="submit" />
</form>
<br />



<?php


$url = (isset($_GET['url']) ?$_GET['url'] : 0);
$str = file_get_contents($url);
####################################################################3
function get_url_contents($url){
        $crl = curl_init();
        $timeout = 5;
        curl_setopt ($crl, CURLOPT_URL,$url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $ret = curl_exec($crl);
        curl_close($crl);
        return $ret;
}
#--------------------------------------Strip html tag----------------------------------------------------
function StripHtmlTags( $text )
{
  // PHP's strip_tags() function will remove tags, but it
  // doesn't remove scripts, styles, and other unwanted
  // invisible text between tags.  Also, as a prelude to
  // tokenizing the text, we need to insure that when
  // block-level tags (such as <p> or <div>) are removed,
  // neighboring words aren't joined.
  $text = preg_replace(
    array(
      // Remove invisible content
      '@<head[^>]*?>.*?</head>@siu',
      '@<style[^>]*?>.*?</style>@siu',
      '@<script[^>]*?.*?</script>@siu',
      '@<object[^>]*?.*?</object>@siu',
      '@<embed[^>]*?.*?</embed>@siu',
      '@<applet[^>]*?.*?</applet>@siu',
      '@<noframes[^>]*?.*?</noframes>@siu',
      '@<noscript[^>]*?.*?</noscript>@siu',
      '@<noembed[^>]*?.*?</noembed>@siu',

      // Add line breaks before & after blocks
      '@<((br)|(hr))@iu',
      '@</?((address)|(blockquote)|(center)|(del))@iu',
      '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
      '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
      '@</?((table)|(th)|(td)|(caption))@iu',
      '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
      '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
      '@</?((frameset)|(frame)|(iframe))@iu',
    ),
    array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",),$text );

  // Remove all remaining tags and comments and return.
  return strtolower( $text );
}

function RemoveComments( & $string )
{
  $string = preg_replace("%(#|;|(//)).*%","",$string);
  $string = preg_replace("%/\*(?:(?!\*/).)*\*/%s","",$string); // google for negative lookahead
  return $string;
}


$html = StripHtmlTags($str);

###Remove number in html################
$html  = preg_replace("/[0-9]/", " ", $html);

#replace &nbsp; by ' '
$html = str_replace("&nbsp;", " ", $html);

######remove any words################

$remove_word = file("stopwordsempty.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach($remove_word as $word) {
	$html = preg_replace("/\b". $word ."\b/", " ", $html);
}
######remove space
$html =  preg_replace ('/<[^>]*>/', '', $html);

$html =  preg_replace('/\b\s+/', ', ', $html);
$html =  preg_replace('/[\b\W]+/',', ',$html);   // Strip off spaces and non-alpha-numeric 

#remove white space, Keep : . ( ) : &
//$html = preg_replace('/\s+/', ', ', $html);


###process#########################################################################
$array_loop = explode(",", $html);
$array_loop1 = $array_loop;
$arr_tem = array();

foreach($array_loop as $key=>$val) {
	if(in_array($val, $array_loop1)) {
		if(!$arr_tem[$val]) $arr_tem[$val] = 0;
		$arr_tem[$val] += 1;
		
		if ( ($k = array_search($val, $array_loop1) ) !== false )
		unset($array_loop1[$k]);
	}
}

arsort($arr_tem);

###echo top 20 words############################################################
echo "<h3>Top 20 words used most</h3>";
$i = 1;
foreach($arr_tem as $key=>$val) {
	if($i<=20) {
		echo $i.":&nbsp;&nbsp;".$key." (".$val." words)<br />";
		$i++;
	}else break;
}
echo "<hr />";
###print array#####################################################################
echo (implode(", ", array_keys($arr_tem)));

?>
