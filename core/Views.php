<?php

namespace Core;

/**
 * Views Core class - Manage every view files and Render view fils.
 * Warning - Do not delete this file.
 * @since 1.0.0
 */
class Views{
    public static function render(string $modulePath, $data = [])
    {
        $viewFile = $modulePath . '/view.phtml';
        
        if (!file_exists($viewFile)) {
            trigger_error("View file not found: " . $viewFile, E_USER_ERROR);
            exit;
        }

        // Extract $data to variables
        extract($data, EXTR_SKIP);

        // Start output buffering
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}