<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ==============================================================
 *
 * Rust
 *
 * ==============================================================
 *
 * @copyright  2022 Pascal HAVÉ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('application/libraries/LanguageTask.php');

class Rust_Task extends Task {

    public function __construct($filename, $input, $params) {
        parent::__construct($filename, $input, $params);
        $this->default_params['memorylimit'] = 500; // MB
    }

    public static function getVersionCommand() {
        return array('rustc --version', '/rustc ([0-9.]*) \(.*\)/');
    }

    public function compile() {
        $src = basename($this->sourceFileName);
        $this->executableFileName = $execFileName = "$src.exe";
        $compileargs = $this->getParam('compileargs');
        $linkargs = $this->getParam('linkargs');
        $cmd = "rustc " . implode(' ', $compileargs) . " -o $execFileName $src " . implode(' ', $linkargs);
        $cmd = "CARGO_HOME=".getenv("CARGO_HOME")." RUSTUP_HOME=".getenv("RUSTUP_HOME")." $cmd";
        list($output, $this->cmpinfo) = $this->run_in_sandbox($cmd);
    }


    // A default name for C++ programs
    public function defaultFileName($sourcecode) {
        return 'prog.rs';
    }


    // The executable is the output from the compilation
    public function getExecutablePath() {
        return "./" . $this->executableFileName;
    }


    public function getTargetFile() {
        return '';
    }
};
