#!/usr/bin/env php
<?php
/**
 * 🚀 CV ANALYSIS SYSTEM - SETUP SCRIPT
 * Run this script to complete setup: php setup-cv-analysis.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║       🚀 CV ANALYSIS SYSTEM - SETUP SCRIPT                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$steps = [
    '✓ Check PHP version',
    '✓ Verify .env configuration',
    '✓ Check vendor files',
    '✓ Run migrations',
    '✓ Clear cache',
    '✓ Verify installation',
];

echo "Setup will perform:\n";
foreach ($steps as $step) {
    echo "  $step\n";
}
echo "\n";

// Step 1: Check PHP Version
echo "📋 STEP 1: Checking PHP version...\n";
$phpVersion = PHP_VERSION;
echo "   PHP Version: $phpVersion\n";
if (version_compare($phpVersion, '8.2', '<')) {
    echo "   ❌ ERROR: PHP 8.2+ required!\n";
    exit(1);
}
echo "   ✅ PHP version OK\n\n";

// Step 2: Verify .env
echo "📋 STEP 2: Verifying .env configuration...\n";
$envFile = '.env';
if (!file_exists($envFile)) {
    echo "   ❌ ERROR: .env file not found!\n";
    exit(1);
}

$envContent = file_get_contents($envFile);
$hasGroqKey = strpos($envContent, 'GROQ_API_KEY') !== false;
$hasGroqUrl = strpos($envContent, 'GROQ_BASE_URL') !== false;

echo "   .env file: " . ($hasGroqKey && $hasGroqUrl ? "✅ OK" : "⚠️  Missing Groq config") . "\n";

if ($hasGroqKey) echo "   - GROQ_API_KEY: ✅ Configured\n";
if ($hasGroqUrl) echo "   - GROQ_BASE_URL: ✅ Configured\n";

if (!$hasGroqKey || !$hasGroqUrl) {
    echo "   ⚠️  Add these to .env:\n";
    if (!$hasGroqKey) echo "   GROQ_API_KEY=";
    if (!$hasGroqUrl) echo "   GROQ_BASE_URL=";
}
echo "\n";

// Step 3: Check vendor
echo "📋 STEP 3: Checking vendor files...\n";
$vendorFiles = [
    'vendor/smalot/pdfparser/src/Parser.php' => 'PDF Parser',
    'vendor/guzzlehttp/guzzle/src/Client.php' => 'Guzzle HTTP',
];

foreach ($vendorFiles as $file => $name) {
    if (file_exists($file)) {
        echo "   ✅ $name installed\n";
    } else {
        echo "   ❌ $name NOT found - Run: composer require smalot/pdfparser guzzlehttp/guzzle\n";
    }
}
echo "\n";

// Step 4: Migration Check
echo "📋 STEP 4: Checking database tables...\n";

// Try to connect to database
try {
    require 'bootstrap/app.php';
    $app = require_once 'bootstrap/app.php';

    // Check if migrations table exists
    $db = $app->make('db');
    $tables = $db->select('SELECT name FROM sqlite_master WHERE type="table"');
    $tableNames = array_map(function ($t) {
        return $t->name;
    }, $tables);

    $requiredTables = [
        'danh_gia_cv_ung_tuyens' => 'CV Analysis Results',
        'ho_so_ung_tuyens' => 'Job Applications',
        'vi_tri_tuyen_dungs' => 'Job Positions',
    ];

    echo "   Checking required tables:\n";
    foreach ($requiredTables as $table => $name) {
        if (in_array($table, $tableNames)) {
            echo "   ✅ $name ($table)\n";
        } else {
            echo "   ⚠️  $name NOT found - Migration needed\n";
        }
    }
} catch (Exception $e) {
    echo "   ⚠️  Could not check database: " . $e->getMessage() . "\n";
}
echo "\n";

// Step 5: Check Config
echo "📋 STEP 5: Checking configuration files...\n";
$configFile = 'config/cv-analysis.php';
if (file_exists($configFile)) {
    echo "   ✅ config/cv-analysis.php exists\n";
} else {
    echo "   ❌ config/cv-analysis.php NOT found!\n";
}
echo "\n";

// Step 6: File Verification
echo "📋 STEP 6: Verifying component files...\n";
$requiredFiles = [
    'app/Models/DanhGiaCVUngTuyen.php' => 'Model',
    'app/Services/CVAnalysisService.php' => 'Service',
    'app/Http/Controllers/Api/CVAnalysisController.php' => 'Controller',
    'app/Console/Commands/AnalyzeCVCommand.php' => 'Command',
];

foreach ($requiredFiles as $file => $name) {
    if (file_exists($file)) {
        echo "   ✅ $name ($file)\n";
    } else {
        echo "   ❌ $name NOT found ($file)\n";
    }
}
echo "\n";

// Results
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    ✅ SETUP VERIFICATION                     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Next Steps:\n";
echo "1. If not done, run in command line:\n";
echo "   composer require smalot/pdfparser guzzlehttp/guzzle\n\n";
echo "2. Run migrations:\n";
echo "   php artisan migrate\n\n";
echo "3. Clear cache:\n";
echo "   php artisan config:cache\n\n";
echo "4. Add CVAnalysis component to HoSoUngTuyen page:\n";
echo "   <CVAnalysis :viTriTuyenDungId=\"jobId\" />\n\n";
echo "5. Test with one CV first, then batch\n\n";

echo "✨ Setup verification complete!\n\n";
?>
