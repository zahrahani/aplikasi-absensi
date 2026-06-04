<?php 
/**
 * =====================================================
 * HELPER FUNCTIONS - RESPONSE CLASS
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

// Mengembalikan json
function responseJson($dataAssoc = [], $status = 200) {
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');
	header("Access-Control-Allow-Origin: *");

	http_response_code($status);

	echo json_encode($dataAssoc);
}

// Mengembalikan file
function responseFile($filePath) {
	if (!file_exists($filePath)) {
		http_response_code(404);
		echo "File tidak ditemukan";
		exit;
	}

// ambil mime type
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($finfo, $filePath);
	finfo_close($finfo);

	header('Content-Description: File Transfer');
	header('Content-Type: ' . $mime);
	header('Content-Length: ' . filesize($filePath));
	header('Content-Disposition: inline; filename="' . basename($filePath) . '"');

// kirim file
	readfile($filePath);
	exit;
}

// handle request post json
function requestJson() {

    static $data = null;

    if ($data === null) {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );
    }

    return $data;
}