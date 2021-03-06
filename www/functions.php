<?

/**
 * Check if the given arch is valid or not
 */
function valid_arch($strArch)
{
    $arrAllowedArch = array('amd64', 'i386');
    
    // check if the given arch is allowed
    if (in_array($strArch, $arrAllowedArch) === false)
    {
        die('Invalid arch');
    }
}

/**
 * Check if the given dist is valid or not
 */
function valid_dist($strDist)
{
    $arrAllowedDist = array('stable', 'testing', 'unstable', 'sid', 'wheezy', 'jessie',
                            "trusty", "saucy", "raring", "quantal", "precise", "oneiric", "natty", "maverick", "lucid", "karmic", "jaunty", "hardy");

    // check if the diven dist is allowed
    if (in_array($strDist, $arrAllowedDist) === false)
    {
        die('Invalid dist');
    }
}


/**
 * Clean all $_GET and $_POST params
 * so they can be used in all other parts
 * of the script.
 *
 * @param	void
 * @return	void
 */
function cleanParams()
{
	// define your tmp variables
	$arrGet = array();
	$arrPost = array();

	// get all values in $_GET
	foreach ((array) array_keys($_GET) as $v)
	{
		// clean the values
		$arrGet[htmlspecialchars($v)] = filter_input(INPUT_GET, $v, FILTER_SANITIZE_SPECIAL_CHARS|FILTER_SANITIZE_ENCODED);
	}


	// get all values in $_POST
	foreach ((array) array_keys($_POST) as $v)
	{
		// clean the values
		$arrPost[htmlspecialchars($v)] = filter_input(INPUT_POST, $v, FILTER_SANITIZE_SPECIAL_CHARS|FILTER_SANITIZE_ENCODED);
	}


	// clean the arrays and write the clean values back
	$_GET = array();
	$_POST = array();

	$_GET = $arrGet;
	$_POST = $arrPost;


    // if there is a dist or an arch, check if the values are allowed or not
    if ($_GET['arch'] != '')
    {
        valid_arch($_GET['arch']);
    }

    if ($_GET['dist'] != '')
    {
        valid_dist($_GET['dist']);
    }

	// cleanup
	unset($arrGet, $arrPost);
}

function size_formatted($file) {
    if (file_exists($file)) {
        $size = filesize($file);
        if ($size < 1024)
            return number_format($size,0)." bytes";
        elseif ($size < (1024 * 1024))
            return number_format(($size / 1024),0)." KB";
        else
            return number_format(($size / 1024 / 1024),1). " MB";
    }
}

function match($scheda, $pattern) {
    preg_match($pattern, $scheda, $result);
    if (count($result) > 1)
        return $result[1];
    else
        return "";
}

function startswith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}

function endswith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
}

function package_from_name($packagename, $packages) {
    foreach($packages as $package)
        if ($packagename == $package['Package'])
            return $package;
}

function icon($icon) {
    return "<img src='img/".$icon.".png'>";
}

function icon_from_file($file) {
    $path_parts = pathinfo($file);
    $extension = $path_parts['extension'];
    $icon = "ext/".$extension;
    if (file_exists("img/".$icon.".png"))
        return icon($icon);
    else
        return icon("ext/unknown");
}

?>
