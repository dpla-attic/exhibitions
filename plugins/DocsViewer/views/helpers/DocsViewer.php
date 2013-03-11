<?php
/**
 * Docs Viewer
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package DocsViewer\View\Helper
 */
class DocsViewer_View_Helper_DocsViewer extends Zend_View_Helper_Abstract
{
    // http://docs.google.com/support/bin/answer.py?hl=en&answer=1189935
    protected $_supportedFiles = array(
        'doc', 'docx', // Microsoft Word
        'ppt', 'pptx', // Microsoft PowerPoint
        'xls', 'xlsx', // Microsoft Excel
        'tif', 'tiff', // Tagged Image File Format
        'eps', 'ps', // PostScript
        'pdf', // Adobe Portable Document Format
        'pages', // Apple Pages
        'ai', // Adobe Illustrator
        'psd', // Adobe Photoshop
        'dxf', // Autodesk AutoCad
        'svg', // Scalable Vector Graphics
        'ttf', // TrueType
        'xps', // XML Paper Specification
    );
    
    /**
     * Return a Google document viewer for the provided files.
     * 
     * @param File|array $files A File record or an array of File records.
     * @param int $width The width of the document viewer in pixels.
     * @param int $height The height of the document viewer in pixels.
     * @return string|null
     */
    public function docsViewer($files, $width = 500, $height = 600)
    {
        if (!is_array($files)) {
            $files = array($files);
        }
        
        // Filter out invalid documents.
        $docs = array();
        foreach ($files as $file) {
            // A valid document must be a File record.
            if (!($file instanceof File)) {
                continue;
            }
            // A valid document must have a supported extension.
            $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $this->_supportedFiles)) {
                continue;
            }
            $docs[] = $file;
        }
        
        // Return if there are no valid documents.
        if (!$docs) {
            return;
        }
        
        return $this->view->partial('common/docs-viewer.php', array(
            'docs' => $docs, 'width' => $width, 'height' => $height, 
        ));
    }
}
