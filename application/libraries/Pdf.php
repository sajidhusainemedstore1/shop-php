<?php
require_once APPPATH . 'third_party/tcpdf_min/tcpdf.php';

class Pdf extends TCPDF {
    public function __construct() {
        parent::__construct();
    }
}
