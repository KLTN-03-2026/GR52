#!/bin/bash

###############################################################################
#  CV ANALYSIS SYSTEM - LINUX/MAC SETUP SCRIPT
#  Run: chmod +x setup-cv-analysis.sh && ./setup-cv-analysis.sh
###############################################################################

echo ""
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║     🚀 CV ANALYSIS SYSTEM - SETUP (Linux/Mac)                ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "ERROR: Please run this script from qlns_be folder!"
    echo "cd /path/to/qlns_be"
    exit 1
fi

# Step 1: Install dependencies
echo "📋 STEP 1: Installing PHP Dependencies..."
echo ""
composer require smalot/pdfparser guzzlehttp/guzzle
if [ $? -ne 0 ]; then
    echo "ERROR: Composer install failed!"
    exit 1
fi
echo "✅ Dependencies installed!"
echo ""

# Step 2: Run migrations
echo "📋 STEP 2: Running Migrations..."
echo ""
php artisan migrate
if [ $? -ne 0 ]; then
    echo "ERROR: Migration failed!"
    exit 1
fi
echo "✅ Migrations completed!"
echo ""

# Step 3: Clear cache
echo "📋 STEP 3: Clearing Cache..."
echo ""
php artisan optimize:clear
echo "✅ Cache cleared!"
echo ""

# Step 4: Run setup verification
echo "📋 STEP 4: Running Setup Verification..."
echo ""
php setup-cv-analysis.php
echo ""

# Done
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║                   ✅ SETUP COMPLETE!                          ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""
echo "📝 Next Steps:"
echo "   1. Add CVAnalysis component to HoSoUngTuyen page"
echo "   2. Test with one CV"
echo "   3. Test batch with multiple CVs"
echo "   4. Deploy when ready!"
echo ""
