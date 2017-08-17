<?php

namespace Ballen\Piplex\Services;


class AudioService
{

    /**
     * The audio player binary path (with trailing slash)
     *
     * @var string
     */
    public $audioPlayerBin = '/usr/bin/play';

    /**
     * The sound path (with trailing slash)
     *
     * @var string
     */
    public $soundPath = 'resources/sounds/';

    /**
     * Array of pheonetic characters that the audio service can output.
     *
     * @var array
     */
    private $pheonetics = [
        '0' => '0.wav',
        '1' => '1.wav',
        '2' => '2.wav',
        '3' => '3.wav',
        '4' => '4.wav',
        '5' => '5.wav',
        '6' => '6.wav',
        '7' => '7.wav',
        '8' => '8.wav',
        '9' => '9.wav',
        '-' => 'dash.wav',
        '.' => 'decimal.wav',
        '/' => 'slash.wav',
        '\\' => 'slash.wav',
        '*' => 'star/wav',
        'a' => 'phonetic_a.wav',
        'b' => 'phonetic_b.wav',
        'c' => 'phonetic_c.wav',
        'd' => 'phonetic_d.wav',
        'e' => 'phonetic_e.wav',
        'f' => 'phonetic_f.wav',
        'g' => 'phonetic_g.wav',
        'h' => 'phonetic_h.wav',
        'i' => 'phonetic_i.wav',
        'j' => 'phonetic_j.wav',
        'k' => 'phonetic_k.wav',
        'l' => 'phonetic_l.wav',
        'm' => 'phonetic_m.wav',
        'n' => 'phonetic_n.wav',
        'o' => 'phonetic_o.wav',
        'p' => 'phonetic_p.wav',
        'q' => 'phonetic_q.wav',
        'r' => 'phonetic_r.wav',
        's' => 'phonetic_s.wav',
        't' => 'phonetic_t.wav',
        'u' => 'phonetic_u.wav',
        'v' => 'phonetic_v.wav',
        'w' => 'phonetic_w.wav',
        'x' => 'phonetic_x.wav',
        'y' => 'phonetic_y.wav',
        'z' => 'phonetic_z.wav',
    ];

    /**
     * Array of pheonetic numbers that the audio service can output.
     *
     * @var array
     */
    private $pheoneticNumbers = [
        '0' => '0.wav',
        '1' => '1.wav',
        '2' => '2.wav',
        '3' => '3.wav',
        '4' => '4.wav',
        '5' => '5.wav',
        '6' => '6.wav',
        '7' => '7.wav',
        '8' => '8.wav',
        '9' => '9.wav',
        '2_' => '2X.wav',
        '3_' => '3X.wav',
        '4_' => '4X.wav',
        '5_' => '5X.wav',
        '6_' => '6X.wav',
        '7_' => '7X.wav',
        '8_' => '8X.wav',
        '9_' => '9X.wav',
        '10' => '10.wav',
        '11' => '11.wav',
        '12' => '12.wav',
        '13' => '13.wav',
        '14' => '14.wav',
        '15' => '15.wav',
        '16' => '16.wav',
        '17' => '17.wav',
        '18' => '18.wav',
        '19' => '19.wav',
        '20' => '20.wav',
        '30' => '30.wav',
        '40' => '40.wav',
        '50' => '50.wav',
        '60' => '60.wav',
        '70' => '70.wav',
        '80' => '80.wav',
        '90' => '90.wav',
        '100' => '100.wav',
        '200' => '200.wav',
        '300' => '300.wav',
        '400' => '400.wav',
        '500' => '500.wav',
        '600' => '600.wav',
        '700' => '700.wav',
        '800' => '800.wav',
        '900' => '900.wav',
    ];

    /**
     * Play the specified courtesy tone.
     *
     * @param $tone The tone filename (without the file extenion)
     * @return void
     */
    public function tone($tone)
    {
        if (file_exists($this->soundPath . 'courtesy_tones/' . $tone . '.wav')) {
            $this->play(' ' . $this->soundPath . 'courtesy_tones/' . $tone . '.wav');
        }
    }

    /**
     * Output the repeater identification.
     *
     * @param $callsign The repeater callsign.
     * @param null $pl The PL/CTCSS tone to access the repeater on (optional)
     * @param bool $withTime Specify if to speak the time with the ident.
     * @param bool $withMorse Specify if to output the morse code translation for the callsign.
     * @return void
     */
    public function ident($callsign, $pl = null, $withTime = false, $withMorse = false)
    {
        $speakArray = [];
        $speakArray[] = $this->soundPath . 'core/repeater.wav';
        $speakArray = array_merge($speakArray, [
            $this->speak($callsign),
        ]);
        if ($pl) {
            $speakArray[] = $this->soundPath . 'core/pl_is.wav';
            $parts = explode('.', $pl);
            //die(var_dump($parts));
            $speakArray = array_merge($speakArray, [$this->speakNumber($parts[0])]);
            $speakArray = array_merge($speakArray, [$this->speak('.')]);
            $speakArray = array_merge($speakArray, [$this->speakNumber($parts[1])]);
        }
        if ($withTime) {
            $speakArray[] = $this->soundPath . 'core/the_time_is.wav';
            $speakArray = array_merge($speakArray, [$this->speakNumber(date('H'))]);
            $speakArray = array_merge($speakArray, [$this->speakNumber(date('i'))]);
            // Could update this later to include "AM" or "PM" but at the moment uses 24 hour clock format.
        }
        if ($withMorse) {
            $speakArray[] = $this->morse($callsign);
        }
        $this->play($this->sequenceOutput($speakArray));
    }

    /**
     * Converts a string of text to a morse code
     *
     * @param $string
     * @return string
     */
    public function morse($string)
    {
        // @TODO - Find a morse code generator binary.

        // Generate the morse code and play and return the file name and path.
        return '';
    }

    /**
     * Reads the given string in the pheonetic alphabet.
     *
     * @param $string The string of characters to read.
     * @return void
     */
    public function say($string)
    {
        $this->play($this->speak($string));
    }

    /**
     * Converts a number to a spoken file array.
     *
     * @param int $number The input number
     * @return void
     */
    public function sayNumber($number)
    {
        $this->play($this->speakNumber($number));
    }

    /**
     * Converts text characters to file array.
     *
     * @param string $string The input string
     * @return array
     */
    private function speak($string)
    {
        $speakArray = [];
        foreach (str_split($string) as $character) {
            $character = strtolower($character);
            if (isset($this->pheonetics[$character])) {
                $speakArray[] = $this->soundPath . 'pheonetics/' . $this->pheonetics[$character];
            }
        }
        return $this->sequenceOutput($speakArray);
    }

    /**
     * Converts number characters to file array.
     *
     * @param int $number The input number
     * @return array
     */
    private function speakNumber($number)
    {
        $speakArray = []; // Temporary storage for the file playlist.
        $length = strlen($number); // Get the number of character for the number...


        // Number is direct and we'll just convert to the sound path...
        if (in_array($number, $this->pheoneticNumbers)) {
            return $this->sequenceOutput($this->soundPath . 'pheonetics/' . $number . '.wav');
        }

        // Number is a variation of multiples...
        if ($length > 3) {
            // Number is greater then 999 - Speak it individually...
            return $this->speak($number);
        }

        // Number is a XXX number, get the first number and output that and the decode and output the second part...
        if ($length > 2) {
            $number = str_split($number);
            $speakArray[] = $this->soundPath . 'pheonetics/' . $number[0] . '00.wav';
            $number = $number[1] . $number[2];
        }

        // Number is just a two digit number but did not match a pre-recorded value, we'll compute and return...
        if ($length = 2) {
            $number = str_split($number);
            $tenDigit = $number[0];
            $lowerDigit = $number[1];
            if (count($speakArray) > 0) {
                // Add an 'and' speak
                $speakArray[] = $this->soundPath . 'pheonetics/and.wav';
            }
            if ($tenDigit != '0') {
                $speakArray[] = $this->soundPath . 'pheonetics/' . $tenDigit . 'X.wav';
            }
        }
        $speakArray[] = $this->soundPath . 'pheonetics/' . $lowerDigit . '.wav';
        return $this->sequenceOutput($speakArray);
    }

    /**
     * Returns sequence of audio files.
     *
     * @param array $files
     * @return array
     */
    private function sequenceOutput($files)
    {
        if (is_array($files)) {
            $cliArgs = '';
            foreach ($files as $file) {
                $cliArgs .= ' ' . $file;
            }
        } else {
            $cliArgs = ' ' . $files;
        }
        return $cliArgs;
    }

    /**
     * Execute the audio player command
     *
     * @param $files The string of audio files to play in order.
     * @return void
     */
    private function play($files)
    {
        system($this->audioPlayerBin . $files);
    }

}