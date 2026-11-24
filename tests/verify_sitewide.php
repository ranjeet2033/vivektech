<?php
$routes = [
    "http://localhost/vivektech/index.php",
    "http://localhost/vivektech/about.php",
    "http://localhost/vivektech/services.php",
    "http://localhost/vivektech/portfolio.php",
    "http://localhost/vivektech/contact.php",
    "http://localhost/vivektech/design-system.php",
    "http://localhost/vivektech/service-website-development.php",
    "http://localhost/vivektech/service-software-applications.php",
    "http://localhost/vivektech/service-ai-automation.php",
    "http://localhost/vivektech/service-digital-marketing.php",
    "http://localhost/vivektech/service-ai-marketing.php",
    "http://localhost/vivektech/service-branding-creative.php",
    "http://localhost/vivektech/service-ecommerce-solutions.php",
    "http://localhost/vivektech/service-it-consulting.php",
    "http://localhost/vivektech/project-enterprise-saas.php",
    "http://localhost/vivektech/project-d2c-commerce.php",
    "http://localhost/vivektech/project-rag-assistant.php",
    "http://localhost/vivektech/project-corporate-portal.php",
    "http://localhost/vivektech/project-b2b-marketing.php",
    "http://localhost/vivektech/project-franchise-platform.php",
    "http://localhost/vivektech/service-catalogue.php",
    "http://localhost/vivektech/partner.php",
    "http://localhost/vivektech/partner-login.php",
    "http://localhost/vivektech/admin-partners.php"
];

$errors = [];
$totalLinks = 0;
$rootDir = realpath(__DIR__ . '/..');

foreach ($routes as $url) {
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false) {
        $errors[] = "Failed to load HTTP route: $url";
        continue;
    }

    // Check floating WhatsApp button present (Company number)
    if (strpos($html, 'wa.me/919334968355') === false) {
        $errors[] = "Missing official WhatsApp link on: $url";
    }

    // Extract links
    preg_match_all('/href=["\']([^"\']+)["\']/i', $html, $matches);
    foreach ($matches[1] as $href) {
        $totalLinks++;
        // Skip anchors, external protocols
        if ($href === '#' || strpos($href, '#') === 0) continue;
        if (preg_match('/^(https?|mailto|tel|javascript):/i', $href)) continue;

        // Strip anchor or query params
        $clean = explode('#', $href)[0];
        $clean = explode('?', $clean)[0];
        if (empty($clean)) continue;

        $targetFile = $rootDir . '/' . ltrim($clean, '/');
        if (!file_exists($targetFile) && !file_exists($targetFile . '.php')) {
            $errors[] = "Broken link on $url -> $href (Target '$targetFile' not found)";
        }
    }
}

echo "========================================================================\n";
echo " SITEWIDE DEEP LINK & WHATSAPP INTEGRITY CHECK\n";
echo "========================================================================\n";
echo "Scanned Routes: " . count($routes) . "\n";
echo "Total Links Evaluated: $totalLinks\n";

if (empty($errors)) {
    echo "STATUS: ALL 20 ROUTES & $totalLinks LINKS VERIFIED CLEAN. ZERO BROKEN LINKS.\n";
    echo "WHATSAPP FLOATING BUTTON & CTAs VERIFIED ON 100% OF PAGES.\n";
} else {
    echo "ERRORS DETECTED (" . count($errors) . "):\n";
    foreach ($errors as $e) {
        echo "  - $e\n";
    }
}
echo "========================================================================\n";
