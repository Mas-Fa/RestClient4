<?php
use PHPUnit\Framework\TestCase;

class FileTypeTest extends TestCase {

    // 1. File exist
    public function testIndexFileExists() {
        echo "index.php ditemukan ✔\n";
        $this->assertFileExists("index.php");
    }

    // 2. Valid PHP Syntax
    public function testValidSyntax() {
        $output = shell_exec("php -l index.php");
        echo "Syntax PHP valid ✔\n";
        $this->assertStringContainsString("No syntax errors", $output);
    }

    // 3. Response Code 200
    public function testResponseCodeIs200() {
        $headers = get_headers("https://catfact.ninja/fact", 1);
        echo "API catfact.ninja mengembalikan status 200 ✔\n";
        $this->assertStringContainsString("200", $headers[0]);
    }

    // 4. Valid JSON Response
    public function testValidJsonResponse() {
        $json = file_get_contents("https://catfact.ninja/fact");
        echo "JSON valid ✔\n";
        $this->assertJson($json);
    }

    // 5. Cek fakta tidak kosong
    public function testFactNotEmpty() {
        $json = file_get_contents("https://catfact.ninja/fact");
        $data = json_decode($json, true);
        echo "\"fact\" tidak kosong ✔\n";
        $this->assertNotEmpty($data["fact"]);
    }
}
