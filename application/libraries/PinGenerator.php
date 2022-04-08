<?php

// Usage
    // 1) Dynamic length 
    // PinGenerator::generate(8); // J5BST6NQ 
    
    // 2) Using prefix 
    // PinGenerator::generate(6, ”XYZ-”); // XYZ-NT163E 
    
    // 3) Using suffix 
    // PinGenerator::generate(6, ”XYZ-”, “-ABC”); // XYZ-TC2MSD-ABC 
    
    // 4) Without numbers 
    // PinGenerator::generate(6, ””, ””, false); // LNTDRS 
    
    // 5) Without letters 
    // PinGenerator::generate(6, ””, ””, true, false); // 835710 
    
    // 6) With symbols 
    // PinGenerator::generate(6, ””, ””, true, true, true); // #H5&S!7 
    
    // 7) Random register (includes lower and uppercase) 
    // PinGenerator::generate(6, ””, ””, true, true, false, true); // aT4hB2 
    
    // 8) With custom Mask Note: length does not matter 
    // PinGenerator::generate(1, ””, ””, true, true, false, false, “XXXXXX”); // STG6N8 
/**
 * 
 * @author Ibrahim Olanrewaju 
 * @date  2019-03-27
 */


class PinGenerator {
    CONST MIN_LENGTH = 8;
    
    /**
     * MASK FORMAT [XXX-XXX]
     * 'X' this is random symbols
     * '-' this is separator
     *
     * @param array $options
     * @return string
     * @throws Exception
     */


    
    static public function generate($options = []) {
        $length         = (isset($options['length']) ? filter_var($options['length'], FILTER_VALIDATE_INT, ['options' => ['default' => self::MIN_LENGTH, 'min_range' => 1]]) : self::MIN_LENGTH );
        $prefix         = (isset($options['prefix']) ? self::cleanString(filter_var($options['prefix'], FILTER_SANITIZE_STRING)) : '' );
        $suffix         = (isset($options['suffix']) ? self::cleanString(filter_var($options['suffix'], FILTER_SANITIZE_STRING)) : '' );
        $useLetters     = (isset($options['letters']) ? filter_var($options['letters'], FILTER_VALIDATE_BOOLEAN) : true );
        $useNumbers     = (isset($options['numbers']) ? filter_var($options['numbers'], FILTER_VALIDATE_BOOLEAN) : false );
        $useSymbols     = (isset($options['symbols']) ? filter_var($options['symbols'], FILTER_VALIDATE_BOOLEAN) : false );
        $useMixedCase   = (isset($options['mixed_case']) ? filter_var($options['mixed_case'], FILTER_VALIDATE_BOOLEAN) : false );
        $mask           = (isset($options['mask']) ? filter_var($options['mask'], FILTER_SANITIZE_STRING) : false );
        $uppercase    = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
        $lowercase    = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];
        $numbers      = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $symbols      = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?'];
        $characters   = [];
        $gen = '';
        if ($useLetters) {
            if ($useMixedCase) {
                $characters = array_merge($characters, $lowercase, $uppercase);
            } else {
                $characters = array_merge($characters, $uppercase);
            }
        }
        if ($useNumbers) {
            $characters = array_merge($characters, $numbers);
        }
        if ($useSymbols) {
            $characters = array_merge($characters, $symbols);
        }
        if ($mask) {
            for ($i = 0; $i < strlen($mask); $i++) {
                if ($mask[$i] === 'X') {
                    $gen .= $characters[mt_rand(0, count($characters) - 1)];
                } else {
                    $gen .= $mask[$i];
                }
            }
        } else {
            for ($i = 0; $i < $length; $i++) {
                $gen .= $characters[mt_rand(0, count($characters) - 1)];
            }
        }
        return $prefix . $gen . $suffix;
    }
    /**
     * @param int $maxNumberOfPinGens
     * @param array $options
     * @return array
     */
    static public function generate_pins($maxNumberOfGens = 1, $options = []) {
        $gens = [];
        for ($i = 0; $i < $maxNumberOfGens; $i++) {
            $temp = self::generate($options);
            $gens[] = $temp;
        }
        return $gens;
    }
    /**
     * @param int $maxNumberOfPinGens
     * @param $filename
     * @param array $options
     */
    static public function generate_pins_to_xls($maxNumberOfGens = 1, $filename, $options = []) {
        $filename = (empty(trim($filename)) ? 'gens' : trim($filename));
        header('Content-Type: application/vnd.ms-excel');
        echo 'Generator Codes' . "\t\n";
        for ($i = 0; $i < $maxNumberOfGens; $i++) {
            $temp = self::generate($options);
            echo $temp . "\t\n";
        }
        header('Content-disposition: attachment; filename=' . $filename . '.xls');
    }
    /**
     * Strip all characters but letters and numbers
     * @param $string
     * @param array $options
     * @return string
     * @throws Exception
     */
    static private function cleanString($string, $options = []) {
        $toUpper = (isset($options['uppercase']) ? filter_var($options['uppercase'], FILTER_VALIDATE_BOOLEAN) : false);
        $toLower = (isset($options['lowercase']) ? filter_var($options['lowercase'], FILTER_VALIDATE_BOOLEAN) : false);
        $striped = preg_replace('/[^a-zA-Z0-9]/', '', trim($string));
        // make uppercase
        if ($toLower && $toUpper) {
            throw new Exception('You cannot set both options (uppercase|lowercase) to "true"!');
        } else if ($toLower) {
            return strtolower($striped);
        } else if ($toUpper) {
            return strtoupper($striped);
        } else {
            return $striped;
        }
    }
}