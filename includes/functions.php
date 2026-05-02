<?php
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/translations.php';

// Gestion de la langue
function setLanguage($lang = null) {
    if ($lang && in_array($lang, ['fr', 'ar', 'en', 'zgh'])) {
        $_SESSION['lang'] = $lang;
    } elseif (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'ar', 'en', 'zgh'])) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

// Appel automatique si pas appelé manuellement
if (isset($_GET['lang'])) {
    setLanguage($_GET['lang']);
}

// Libérer le verrou de session si on n'est pas dans l'administration
if (strpos($_SERVER['PHP_SELF'], '/admin/') === false) {
    session_write_close();
}

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
function resizeImage($sourcePath, $destPath, $maxWidth, $maxHeight, $forceWebP = false) {
    // Vérifier et créer le dossier de destination si nécessaire
    $destDir = dirname($destPath);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    // Vérifier si l'extension GD est installée
    if (!function_exists('imagecreatefromjpeg')) {
        return copy($sourcePath, $destPath);
    }

    $info = getimagesize($sourcePath);
    if (!$info) return false;

    list($width, $height, $type) = $info;
    
    // Calcul du ratio
    $srcRatio = $width / $height;
    $destRatio = $maxWidth / $maxHeight;

    if ($srcRatio > $destRatio) {
        $tempWidth = $maxHeight * $srcRatio;
        $tempHeight = $maxHeight;
    } else {
        $tempWidth = $maxWidth;
        $tempHeight = $maxWidth / $srcRatio;
    }

    switch ($type) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($sourcePath); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($sourcePath); break;
        case IMAGETYPE_WEBP: $src = @imagecreatefromwebp($sourcePath); break;
        default: return copy($sourcePath, $destPath);
    }

    if (!$src) return copy($sourcePath, $destPath);

    $dst = imagecreatetruecolor($maxWidth, $maxHeight);
    
    // Gestion de la transparence
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP || $forceWebP) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $maxWidth, $maxHeight, $transparent);
    }

    // Crop au centre pour respecter exactement le ratio demandé (ex: 3:2)
    $srcX = 0;
    $srcY = 0;
    $srcW = $width;
    $srcH = $height;

    if ($srcRatio > $destRatio) {
        $srcW = $height * $destRatio;
        $srcX = ($width - $srcW) / 2;
    } else {
        $srcH = $width / $destRatio;
        $srcY = ($height - $srcH) / 2;
    }

    imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $maxWidth, $maxHeight, $srcW, $srcH);

    // Sauvegarde
    $success = false;
    if ($forceWebP && function_exists('imagewebp')) {
        // Changer l'extension du fichier de destination si c'est forcé en WebP
        $destPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $destPath);
        $success = imagewebp($dst, $destPath, 80);
    } else {
        switch ($type) {
            case IMAGETYPE_JPEG: $success = imagejpeg($dst, $destPath, 85); break;
            case IMAGETYPE_PNG:  $success = imagepng($dst, $destPath); break;
            case IMAGETYPE_WEBP: $success = imagewebp($dst, $destPath, 85); break;
        }
    }

    imagedestroy($src);
    imagedestroy($dst);
    return $success ? $destPath : false;
}

// Définition de BASE_URL plus robuste
if (!defined('BASE_URL')) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? "https" : "http";
    $server_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Détection de la racine web du projet
    $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    
    // On cherche le chemin relatif du script actuel par rapport à la racine du projet
    // Ici on utilise le fait que ce fichier est TOUJOURS dans /includes/
    $current_file = str_replace('\\', '/', __FILE__);
    $project_root_physical = dirname(dirname($current_file));
    
    // On calcule la profondeur du script actuel
    $script_path_parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
    $depth = count($script_path_parts) - 1;
    
    // On cherche le segment 'admin' ou le nom du dossier root
    // Mais le plus simple : BASE_URL est le protocole + host + dossier racine si présent
    
    // Version ultra-robuste : on utilise la position de /includes/ dans l'URL si elle est présente, 
    // ou on remonte selon la structure connue.
    $root_web_path = '/';
    if ($server_host === 'localhost' || strpos($server_host, '127.0.0.1') !== false) {
        // En local, souvent dans /institue music/
        $script_parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
        if (count($script_parts) > 0) {
            $root_web_path = '/' . $script_parts[0] . '/';
        }
    }
    
    // Sur Railway, le site est à la racine
    if (isset($_SERVER['RAILWAY_STATIC_URL']) || strpos($server_host, 'railway.app') !== false) {
        $root_web_path = '/';
    }

    define('BASE_URL', $protocol . '://' . $server_host . $root_web_path);
}

/**
 * Retourne le chemin absolu vers un asset (image, css, js)
 */
function asset($path) {
    if (!$path) return '';
    if (strpos($path, 'http') === 0) return $path;
    return BASE_URL . ltrim($path, '/');
}

/**
 * Retourne l'URL d'une image avec une image par défaut si elle n'existe pas
 */
function get_image_url($path) {
    if (!$path) return asset('public/images/bg-pattern.png');
    
    // Chemin physique pour la vérification
    $full_path = dirname(__DIR__) . '/' . ltrim($path, '/');
    if (!file_exists($full_path)) {
        return asset('public/images/bg-pattern.png');
    }
    
    return asset($path);
}

/**
 * Gère l'upload et le redimensionnement d'une image
 * Retourne le chemin relatif pour la DB ou false en cas d'erreur
 */
function handleImageUpload($fileField, $oldPath = null) {
    if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
        return $oldPath;
    }

    $file = $_FILES[$fileField];
    $fileSize = $file['size'];
    $fileName = $file['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        $_SESSION['flash_error'] = "Format non supporté (JPG, PNG, WEBP).";
        return false;
    }

    if ($fileSize > 5 * 1024 * 1024) {
        $_SESSION['flash_error'] = "L'image est trop lourde (Max 5 Mo).";
        return false;
    }

    $uploadDir = dirname(__DIR__) . '/public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newFileName = md5(uniqid(rand(), true)) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    // On force le redimensionnement en 1200x800
    $finalPath = resizeImage($file['tmp_name'], $destPath, 1200, 800, true);
    
    if ($finalPath) {
        return 'public/uploads/' . basename($finalPath);
    }

    $_SESSION['flash_error'] = "Erreur lors du traitement de l'image.";
    return false;
}
