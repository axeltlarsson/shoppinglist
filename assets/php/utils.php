<?php
 
	class Colors {
		private $foreground_colors = array();
		private $background_colors = array();
 
		public function __construct() {
			// Set up shell colors
			$this->foreground_colors['black'] = '0;30';
			$this->foreground_colors['dark_gray'] = '1;30';
			$this->foreground_colors['blue'] = '0;34';
			$this->foreground_colors['light_blue'] = '1;34';
			$this->foreground_colors['green'] = '0;32';
			$this->foreground_colors['light_green'] = '1;32';
			$this->foreground_colors['cyan'] = '0;36';
			$this->foreground_colors['light_cyan'] = '1;36';
			$this->foreground_colors['red'] = '0;31';
			$this->foreground_colors['light_red'] = '1;31';
			$this->foreground_colors['purple'] = '0;35';
			$this->foreground_colors['light_purple'] = '1;35';
			$this->foreground_colors['brown'] = '0;33';
			$this->foreground_colors['yellow'] = '1;33';
			$this->foreground_colors['light_gray'] = '0;37';
			$this->foreground_colors['white'] = '1;37';
 
			$this->background_colors['black'] = '40';
			$this->background_colors['red'] = '41';
			$this->background_colors['green'] = '42';
			$this->background_colors['yellow'] = '43';
			$this->background_colors['blue'] = '44';
			$this->background_colors['magenta'] = '45';
			$this->background_colors['cyan'] = '46';
			$this->background_colors['light_gray'] = '47';
		}
 
		// Returns colored string
		public function getColoredString($string, $foreground_color = null, $background_color = null) {
			$colored_string = "";
 
			// Check if given foreground color found
			if (isset($this->foreground_colors[$foreground_color])) {
				$colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
			}
			// Check if given background color found
			if (isset($this->background_colors[$background_color])) {
				$colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
			}
 
			// Add string and end coloring
			$colored_string .=  $string . "\033[0m";
 
			return $colored_string;
		}
 
		// Returns all foreground color names
		public function getForegroundColors() {
			return array_keys($this->foreground_colors);
		}
 
		// Returns all background color names
		public function getBackgroundColors() {
			return array_keys($this->background_colors);
		}
	}

    /**
     *  Outputs a $message to STDOUT and log file, color-coded depending on $case.
     *  @param $message - the message to log
     *  @param $case - the type of severity and reson to log, the message will be color-coded accordingly, the cases are:
     *          userConnected, userDisconnected, addedItem, updatedItem, removedItem, clearedList, synced, error, warn
     */
    function logMsg($message, $level = "default") {
        $colors = new Colors();
        $fg = null;
        $bg = null;
        
        switch ($level) {
            case 'userConnected' :
                $fg = null;
                $bg = "cyan";
                break;
            case 'userDisconnected' :
                $fg = "cyan";
                $bg = "black";
                break;
            case 'addedItem' :
                $fg = "black";
                $bg = "green";
                break;
            case 'updatedItem' :
                $fg = "light_gray";
                $bg = "green";
                break;
            case 'clearedList' :
                $fg = "light_gray";
                $bg = "black";
                break;
            case 'removedItem' :
                $fg = "yellow";
                $bg = "black";
                break;
            case 'error' :
                $fg = "white";
                $bg = "red";
                break;
            case 'warn' :
                $fg = "brown";
                $bg = "magenta";
                break;
            case 'sync' :
                $fg = "light_green";
                $bg = "blue";
                break;
            default : 
                break;
        }
        
        echo  $colors->getColoredString($message, $fg, $bg) . "\n";
        date_default_timezone_set('Europe/Stockholm');
		file_put_contents("/var/www/shoppinglist/logs/logFile", date("{Y-m-d H:i:s}") . " " . $message . "\n", FILE_APPEND);
    }
?>