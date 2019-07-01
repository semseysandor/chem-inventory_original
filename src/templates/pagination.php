<?php if ($last_page > 1) { # If we need pagination at all

	echo '<nav class="block pagination">';

	// Range of num links to show
	$range = 10;

	// Link to page 1
	$get_array['page'] = 1;
	echo '<span><button class="button pagination font-m" ';
	echo js('get', [$javascript.'&'.http_build_query($get_array), 'index']).'.>Első</button></span>'; # JavaScript

	// Link to previous page
	if ($current_page > 1) {
		$prev_page = $current_page - 1;
		$get_array['page'] = $prev_page;
		echo '<span><button class="button pagination font-m" ';
		echo js('get', [$javascript.'&'.http_build_query($get_array), 'index']).'><<</button></span>'; # JavaScript
	}

	// Loop to show links to range of pages around current page
	for ($x = ($current_page - $range); $x < (($current_page + $range) + 1); $x++) {

		if (($x > 0) and ($x <= $last_page)) { # If valid page number

			echo '<span><button class="button pagination font-m';
			if ($x == $current_page) { # If we're on current page
				echo ' selected"><b>'.$x.'</b>'; # Highlight it but don't make a link
			} else { # If not current page
				// Make it a link
				$get_array['page'] = $x;
				echo '" '.js('get', [$javascript.'&'.http_build_query($get_array), 'index']).'>'.$x.'</button></span>'; # JavaScript
			}
			echo '</button></span>';
		}
	}

	// Link to next page
	if ($current_page < $last_page) {
		$next_page = $current_page + 1;
		$get_array['page'] = $next_page;
		echo '<span><button class="button pagination font-m" ';
		echo js('get', [$javascript.'&'.http_build_query($get_array), 'index']).'>>></button></span>'; # JavaScript
	}

	// Link to last page
	$get_array['page'] = $last_page;
	echo '<span><button class="button pagination font-m" ';
	echo js('get', [$javascript.'&'.http_build_query($get_array), 'index']).'>Utolsó</button></span>'; # JavaScript

	echo '</nav>';
}?>