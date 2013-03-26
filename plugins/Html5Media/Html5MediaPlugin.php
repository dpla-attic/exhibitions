<?php
/**
 * @package Html5Media
 * @copyright Copyright 2012, John Flatness
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3 or any later version
 */

class Html5MediaPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('initialize', 'admin_head', 'public_head',
        'config', 'config_form', 'install', 'uninstall', 'upgrade');

    public function hookInstall()
    {
        $defaults = array(
            'video' => array(
                'options' => array(
                    'width' => 480,
                    'height' => 270
                ),
                'types' => array(
                    'video/flv', 'video/x-flv', 'video/mp4', 'video/m4v',
                    'video/webm', 'video/wmv', 'video/quicktime'
                ),
                'extensions' => array('mp4', 'm4v', 'flv', 'webm', 'wmv'),
            ),
            'audio' => array(
                'types' => array(
                    'audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/m4a',
                    'audio/wma'
                ),
                'extensions' => array('mp4', 'm4v', 'flv', 'webm', 'wmv'),
            ),
            'text' => array(
                'types' => array('text/vtt'),
                'extensions' => array('srt', 'vtt')
            ),
            'common' => array(
                'options' => array(
                    'autoplay' => false,
                    'controls' => true,
                    'loop'     => false
                )
            )
        );
        set_option('html5_media_settings', serialize($defaults));
    }

    public function hookUninstall()
    {
        delete_option('html5_media_settings');
    }

    public function hookUpgrade($args)
    {
        $oldVersion = $args['old_version'];
        if (version_compare($oldVersion, '1.1', '<')) {
            $this->hookInstall();
        } 
    }

    public function hookInitialize()
    {
        $settings = unserialize(get_option('html5_media_settings'));
        $commonOptions = $settings['common']['options'];
        add_file_display_callback(array(
            'mimeTypes' => $settings['audio']['types'],
            'fileExtensions' => $settings['audio']['extensions']
            ), 'Html5MediaPlugin::audio',
            $commonOptions);
        add_file_display_callback(array(
            'mimeTypes' => $settings['video']['types'],
            'fileExtensions' => $settings['video']['extensions']
            ), 'Html5MediaPlugin::video',
            $commonOptions + $settings['video']['options']);
        add_file_display_callback(array(
            'mimeTypes' => $settings['text']['types'],
            'fileExtensions' => $settings['text']['extensions']
            ), 'Html5MediaPlugin::text');
    }

    public function hookConfigForm()
    {
        $settings = unserialize(get_option('html5_media_settings'));
        
        $audio = $settings['audio'];
        $audio['types'] = implode(',', $audio['types']);
        $audio['extensions'] = implode(',', $audio['extensions']);
        
        $video = $settings['video'];
        $video['types'] = implode(',', $video['types']);
        $video['extensions'] = implode(',', $video['extensions']);
        
        $text = $settings['text'];
        $text['types'] = implode(',', $text['types']);
        $text['extensions'] = implode(',', $text['extensions']);
        
        include 'config-form.php';
    }

    public function hookConfig()
    {
        $settings = unserialize(get_option('html5_media_settings'));
        
        $audio = $_POST['audio'];
        $settings['audio']['types'] = explode(',', $audio['types']);
        $settings['audio']['extensions'] = explode(',', $audio['extensions']);

        $video = $_POST['video'];
        $settings['video']['options']['width'] = (int) $video['options']['width'];
        $settings['video']['options']['height'] = (int) $video['options']['height'];
        $settings['video']['types'] = explode(',', $video['types']);
        $settings['video']['extensions'] = explode(',', $video['extensions']);

        $text = $_POST['text'];
        $settings['text']['types'] = explode(',', $text['types']);
        $settings['text']['extensions'] = explode(',', $text['extensions']);

        set_option('html5_media_settings', serialize($settings));
    }

    public function hookAdminHead()
    {
        $this->_head();
    }

    public function hookPublicHead()
    {
        $this->_head();
    }

    public static function audio($file, $options)
    {
        return self::_media('audio', $file, $options);
    }

    public static function video($file, $options)
    {
        return self::_media('video', $file, $options);
    }

    public static function text($file, $options)
    {
        return null;
    }

    private function _head()
    {
        queue_js_file('mediaelement-and-player.min', 'mediaelement');
        queue_css_file('mediaelementplayer', 'screen', false, 'mediaelement');
    }

    private static function _media($type, $file, $options)
    {
        static $i = 0;
        $i++;

        $mediaOptions = '';

        if (isset($options['width']))
            //$mediaOptions .= ' width="' . $options['width'] . '"';
            $mediaOptions .= ' width="100%"';
        if (isset($options['height']))
            //$mediaOptions .= ' height="' . $options['height'] . '"';
            $mediaOptions .= ' height="100%"';
        if ($options['autoplay'])
            $mediaOptions .= ' autoplay';
        if ($options['controls'])
            $mediaOptions .= ' controls';
        if ($options['loop'])
            $mediaOptions .= ' loop';

        $filename = html_escape($file->getWebPath('original'));

        $tracks = '';
        foreach (self::_findTextTrackFiles($file) as $textFile) {
            $kind = metadata($textFile, array('Dublin Core', 'Type'));
            $language = metadata($textFile, array('Dublin Core', 'Language'));
            $label = metadata($textFile, array('Dublin Core', 'Title'));

            if (!$kind) {
                $kind = 'subtitles';
            }

            if (!$language) {
                $language = 'en';
            }

            $trackSrc = html_escape($textFile->getWebPath('original'));

            if ($label) {
                $labelPart = ' label="' . $label . '"';
            } else {
                $labelPart = '';
            }

            $tracks .= '<track kind="' . $kind . '" src="' . $trackSrc . '" srclang="' . $language . '"' . $labelPart . '>';
        }

        return <<<HTML
<$type id="html5-media-$i" src="$filename"$mediaOptions>
$tracks
</$type>
<script type="text/javascript">
jQuery('#html5-media-$i').mediaelementplayer({
    audioWidth: '100%'
});
</script>
HTML;
    }

    private static function _findTextTrackFiles($mediaFile)
    {
        $settings = unserialize(get_option('html5_media_settings'));
        $extensions = $settings['text']['extensions'];

        $item = $mediaFile->getItem();
        $mediaName = pathinfo($mediaFile->original_filename,
            PATHINFO_FILENAME);

        $trackFiles = array();
        foreach ($item->Files as $file) {
            if ($file->id == $mediaFile->id) {
                continue;
            }
            $pathInfo = pathinfo($file->original_filename);
            if ($pathInfo['filename'] == $mediaName
                && isset($pathInfo['extension'])
                && in_array($pathInfo['extension'], $extensions)
            ) {
                $trackFiles[] = $file;
            }
        }
        return $trackFiles;
    }
}
