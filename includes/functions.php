<?php
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/translations.php';

/**
 * Fonction de traduction
 */
function __($key) {
    global $translations;
    $lang = $_SESSION['lang'] ?? 'fr';
    return $translations[$lang][$key] ?? ($translations['fr'][$key] ?? $key);
}

/**
 * Retourne la direction du texte (ltr ou rtl)
 */
function getLangDir() {
    global $translations;
    $lang = $_SESSION['lang'] ?? 'fr';
    return $translations[$lang]['dir'] ?? 'ltr';
}

/**
 * Sécurise l'affichage d'une chaîne (Anti-XSS)
 * @param string $string
 * @return string
 */
function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Formate une date selon la langue active
 * @param string $dateString Date MySQL (YYYY-MM-DD)
 * @return string
 */
function formatDate($dateString) {
    if (!$dateString) return '';
    global $translations;
    $lang = $_SESSION['lang'] ?? 'fr';
    $timestamp = strtotime($dateString);
    
    $months = $translations[$lang]['months'] ?? $translations['fr']['months'];
    
    return date('d', $timestamp) . ' ' . $months[date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
}

/**
 * Alias pour compatibilité
 */
function formatDateFR($date) {
    return formatDate($date);
}

/**
 * Tronque un texte proprement pour les résumés
 * @param string $text
 * @param int $length
 * @return string
 */
function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    return $text . '...';
}

/**
 * Redimensionne une image en préservant le ratio
 * @param string $sourcePath
 * @param string $destPath
 * @param int $maxWidth
 * @param int $maxHeight
 * @return bool
 */
function resizeImage($sourcePath, $destPath, $maxWidth, $maxHeight) {
    // Vérifier si l'extension GD est installée
    if (!function_exists('imagecreatefromjpeg')) {
        return copy($sourcePath, $destPath);
    }

    $info = getimagesize($sourcePath);
    if (!$info) return false;

    list($width, $height, $type) = $info;
    $ratio = $width / $height;

    if ($maxWidth / $maxHeight > $ratio) {
        $maxWidth = $maxHeight * $ratio;
    } else {
        $maxHeight = $maxWidth / $ratio;
    }

    switch ($type) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($sourcePath); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($sourcePath); break;
        case IMAGETYPE_WEBP: $src = @imagecreatefromwebp($sourcePath); break;
        default: return copy($sourcePath, $destPath);
    }

    if (!$src) return copy($sourcePath, $destPath);

    $dst = imagecreatetruecolor($maxWidth, $maxHeight);
    
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);

    switch ($type) {
        case IMAGETYPE_JPEG: imagejpeg($dst, $destPath, 85); break;
        case IMAGETYPE_PNG:  imagepng($dst, $destPath); break;
        case IMAGETYPE_WEBP: imagewebp($dst, $destPath, 85); break;
    }

    imagedestroy($src);
    imagedestroy($dst);
    return true;
}

/**
 * Retourne le chemin correct vers un asset (image, css, js)
 * Gère automatiquement le préfixe ../ selon l'emplacement
 */
function asset($path) {
    // Si le chemin commence déjà par http, on le laisse
    if (strpos($path, 'http') === 0) return $path;
    
    // Détecter si on est dans le dossier admin ou un sous-dossier d'admin
    $current_path = $_SERVER['PHP_SELF'];
    $is_admin = (strpos($current_path, '/admin/') !== false);
    
    // Si on est dans admin/actualites/ ou admin/annonces/, on doit monter de 2 niveaux
    $depth = substr_count(trim($current_path, '/'), '/');
    
    // Pour simplifier : si on est dans admin, on rajoute ../ autant de fois que nécessaire
    $prefix = '';
    if ($is_admin) {
        if (strpos($current_path, '/admin/actualites/') !== false || 
            strpos($current_path, '/admin/annonces/') !== false ||
            strpos($current_path, '/admin/galerie/') !== false ||
            strpos($current_path, '/admin/messages/') !== false) {
            $prefix = '../../';
        } else {
            $prefix = '../';
        }
    }
    
    return $prefix . $path;
}
