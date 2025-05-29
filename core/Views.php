<?php

namespace Core;

/**
 * Views Core class - Manage every view files and Render view fils.
 * Warning - Do not delete this file.
 * @since 1.0.0
 */
class Views{
    private static $layouts = [];
    private static $inlets = [];
    private static $currentInlet = null;

    public static function render(string $modulePath, array $data = [])
    {
        $viewFile = $modulePath . '/view.phtml';
        
        if (!file_exists($viewFile)) {
            trigger_error("View file not found: " . $viewFile, E_USER_ERROR);
            Helpers::coreErrorLog("View file not found: " . $viewFile);
            exit;
        }

        self::$layouts = [];
        self::$inlets = [];
        self::$currentInlet = null;

        $viewContent = file_get_contents($viewFile);
        $processedView = self::parseTemplate($viewContent, $data);

        if(!self::$layouts == []){
            $layoutBuffer = [];
            foreach(self::$layouts as $layout){
                $layoutPath = ROOT_DIR . 'app/modules/' . $layout . '.phtml';
                if (!file_exists($layoutPath)) {
                    Helpers::coreErrorLog("Layout file not found: " . $layoutPath, E_WARNING);
                    trigger_error("Layout file not found: " . $layoutPath, E_USER_NOTICE);
                    continue;
                }

                $layoutContent = file_get_contents($layoutPath);
                $processedLayout = self::parseTemplate($layoutContent, $data);

                foreach (self::$inlets as $inletName => $content) {
                    $placeholder = "@outlat($inletName)";
                    $processedLayout = str_replace($placeholder, $content, $processedLayout);
                    unset(self::$inlets[$inletName]);
                }

                $layoutBuffer[] = $processedLayout;
            }
            // Helpers::coreErrorLog(trim(implode($layoutBuffer)));

            $tempViewPath = self::tempViewHandle(trim(implode($layoutBuffer)));
            ob_start();
            extract($data, EXTR_SKIP);
            include $tempViewPath;
            return ob_get_clean();
        } else {
            $tempViewPath = self::tempViewHandle($processedView);
            ob_start();
            extract($data, EXTR_SKIP);
            include $tempViewPath;
            return ob_get_clean();
        }
    }

    /**
     * Parse the templates html
     * 
     * @since 1.0.0
     */
    private static function parseTemplate(string $content, array $data): string
    {
        $content = preg_replace_callback(
            '/@layout\((.*?)\)/',
            function($matches) {
                $layout = implode("/",explode(">",trim($matches[1])));
                if(!in_array($layout, self::$layouts)){
                    self::$layouts[] = $layout;
                }

                // print_r($matches);
                // echo self::$layout;
                // print_r(self::$layout);
                return "";
            },
            $content
        );

        $content = preg_replace_callback(
            '/@inlet\((.*?)\)(.*?)@endinlet/s',
            function($matches) use ($data) {
                $inletName = trim($matches[1]);
                $inletContent = $matches[2];
                // echo $inletName;
                // echo $inletContent;
                // print_r($matches);

                self::$inlets[$inletName] = $inletContent;

                return '';
            },
            $content
        );

        return $content;
    }

    /**
     * Temporary Store The View File .phtml
     * 
     * @since 1.0.0
     */
    private static function tempViewHandle(string $content): string{

        $tempFile = tempnam(sys_get_temp_dir(), 'temp') . '.phtml';
        file_put_contents($tempFile, $content);

        // echo $tempFile;
        // include $tempFile;

        return $tempFile;
    }

}