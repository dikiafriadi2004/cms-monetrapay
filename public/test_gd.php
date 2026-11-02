<?php
echo "<h2>PHP Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "php.ini: " . php_ini_loaded_file() . "<br>";
echo "GD loaded: " . (extension_loaded('gd') ? '<b style=color:green>YES</b>' : '<b style=color:red>NO</b>') . "<br>";
echo "Imagick loaded: " . (extension_loaded('imagick') ? '<b style=color:green>YES</b>' : '<b style=color:red>NO</b>') . "<br>";
echo "<br>";

// Test actual resize
if (extension_loaded('gd')) {
    echo "<h3>GD Test</h3>";
    // Create a test image
    $img = imagecreatetruecolor(100, 100);
    $color = imagecolorallocate($img, 61, 158, 122);
    imagefill($img, 0, 0, $color);
    
    $dst = imagecreatetruecolor(32, 32);
    imagecopyresampled($dst, $img, 0, 0, 0, 0, 32, 32, 100, 100);
    
    ob_start();
    imagepng($dst);
    $data = ob_get_clean();
    
    imagedestroy($img);
    imagedestroy($dst);
    
    echo "GD resize test: <b style=color:green>OK (" . strlen($data) . " bytes)</b><br>";
} else {
    echo "<b style=color:red>GD NOT LOADED — resize will fail!</b><br>";
}

// Check DB favicon
require_once dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

echo "<h3>Favicon Status</h3>";
$fav = DB::table('settings')->where('key', 'favicon')->where('group', 'general')->value('value');
echo "DB value: " . ($fav ?? 'NULL') . "<br>";

$files = Storage::disk('public')->allFiles('favicon');
echo "favicon/ files: " . (empty($files) ? 'KOSONG' : implode(', ', $files)) . "<br>";
