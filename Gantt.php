<?php
/**
 * gantt - File for the gantt class
 *
 * Takes data for events from users to create a basic gantt chart that can
 * be displayed in various formats.
 *
 * @author Andrew Tomaka
 * @version 1.0
 */

/**
 * gantt - Gantt chart class.
 *
 * Manage events added by a user to produce a gantt chart
 *
 * @author Andrew Tomaka
 * @version 1.0
 **/
class Gantt {
	/**
	 * A list of events added by the user.
	 * @access private
	 * @var array
	 **/
	private $events		= array();

	/**
	 * HTML render of our chart
	 * @access private
	 * @var string
	 **/
	private $html 		= '';

	/**
	 * List of colors to use for events
	 * @access private
	 * @var array
	 **/
	 private $colors	= array();

	 /**
	  * Track the number of times we've retrieved colors so that we 
	  * can always return alternating colors.
	  * @access private
	  * @var integer
	  **/
	 private $color_count = 0;

	/**
	 * Constructor
	 **/
	function __construct($colors = true) {
		//add a default set of colors
		if($colors) {
			$this->add_color('#FF8080','#99CCFF','#00FF00','#FF9900','#800080');	
		}
	}

	/**
	 * Add an event to be processed
	 * @param string name of the event
	 * @param integer the week to start this event
	 * @param integer duration
	 * @return bool whether or not the event was added successfully
	 **/
	 public function add_event($event, $start, $duration) {
	 	// return false if we do not have valid data
	 	if(!is_int($start)) return false;
	 	if(!is_int($duration)) return false;

	 	// add the event
	 	$this->events[] = (object)array(
	 		'event'		=> $event,
		 	'start'		=> $start,
		 	'duration'	=> $duration,
		);

		// reset the HTML so it is re-rendered next attempt
		$this->html = '';

	 	return true;
	 }

	 /**
	  * Create an HTML Gantt chart from the current events
	  * @return string html for our gantt chart
	  **/
	public function render_html() {
		// if we have previously rendered this gantt and have not added 
		// new events, we don't need to do it again and can returned the 
		// cached html
		if($this->html != '') {
			return $this->html;
		}

		//require that there is at least one color set.
		if(count($this->colors) == 0) {
			return 'Error! At least one colors must be set: add_colors("#000")';
		}

		$columns = $this->find_last();

		// probably should template this html sometime.
		$this->html = '<table id="gantt">' . "\n";

		// create the header
		$this->html .= '	<tr>' . "\n";
		$this->html .= '		<th>Task</th>' . "\n";
		$this->html .= '		<th>Weeks</th>' . "\n";
		for($i = 1; $i <= $columns; $i++) {
			$this->html .= '		<th>' . $i . '</th>' . "\n";
		}
		$this->html .= '	</tr>' . "\n";

		// add all the rows
		foreach($this->events as $event) {
			$color = $this->get_color();
			$this->html .= '	<tr>' . "\n";
			$this->html .= '		<td>' . $event->event . '</td>' . "\n";
			$this->html .= '		<td>' . $event->duration . '</td>' . "\n";
			for($i = 1; $i <= $columns; $i++) {
				if($i >= $event->start && $i < ($event->start + $event->duration)) {
					$style = ' style="background-color:' . $color . '"';
				} else {
					$style = ' style="background-color:#fff"';
				}
				$this->html .= '		<td class="colored"'. $style .'>&nbsp;</td>' . "\n";
			}
			$this->html .= '	</tr>' . "\n";
		}
		$this->html .= '</table>' . "\n";

		return $this->html;
	}

	/**
	 * Find the last column in our Gantt chart
	 * @return integer last column
	 **/
	private function find_last() {
		$last = 0;

		foreach($this->events as $event) {
			$end = $event->start + $event->duration - 1;
			$last = ($end > $last) ? $end : $last;
		}

		return $last;
	}

	/**
	 * Add a color to our list
	 * @param string hexadecimal color code, 1..n accepted
	 **/
	public function add_color() {
		$colors = func_get_args();

		if(empty($colors)) return;

		foreach($colors as $color) {
			// the color was not properly formatted
			if(preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/',$color) == 0) continue;
			// color is already in the list
			if(in_array($color,$this->colors)) continue;

			$this->colors[] = $color;
		}
	}

	/**
	 * Get a color to use
	 * @param integer the number of times this function has been called
	 * @return string color code
	 **/
	private function get_color() {
		return $this->colors[++$this->color_count % count($this->colors)];
	}
}
?>