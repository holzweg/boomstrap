<?php
/**
 * Boomstrap
 *
 * @category    extension
 * @package     boomstrap
 * @copyright   Copyright (c) 2012 Holzweg e-commerce solutions (http://www.holzweg.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 */

/**
 * Extends HTML head block for LESS support
 *
 * @category    extension
 * @package     boomstrap
 * @copyright   Copyright (c) 2012 Holzweg e-commerce solutions (http://www.holzweg.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 */
class Holzweg_Boomstrap_Block_Html_Head extends Mage_Page_Block_Html_Head
{
    /**
     * Add LESS file to HEAD entity
     *
     * @param string $name
     * @param string $params
     * @return Holzweg_Boomstrap_Block_Html_Head
     */
    public function addLess($name, $params = "")
    {
        $this->addItem('skin_less', $name, $params);
        return $this;
    }

    /**
     * Add LESS file for Internet Explorer only to HEAD entity
     *
     * @param string $name
     * @param string $params
     * @return Holzweg_Boomstrap_Block_Html_Head
     */
    public function addLessIe($name, $params = "")
    {
        $this->addItem('skin_less', $name, $params, 'IE');
        return $this;
    }

    /**
     * Get HEAD HTML with CSS/LESS/JS/RSS definitions
     * (actually it also renders other elements, TODO: fix it up or rename this method)
     *
     * @return string
     */
    public function getCssJsHtml()
    {
        // separate items by types
        $lines  = array();
        foreach ($this->_data['items'] as $item) {
            if (!is_null($item['cond']) && !$this->getData($item['cond']) || !isset($item['name'])) {
                continue;
            }
            $if     = !empty($item['if']) ? $item['if'] : '';
            $params = !empty($item['params']) ? $item['params'] : '';
            switch ($item['type']) {
                case 'js':        // js/*.js
                case 'skin_js':   // skin/*/*.js
                case 'js_css':    // js/*.css
                case 'skin_css':  // skin/*/*.css
                case 'skin_less': // skin/*/*.less
                    $lines[$if][$item['type']][$params][$item['name']] = $item['name'];
                    break;
                default:
                    $this->_separateOtherHtmlHeadElements($lines, $if, $item['type'], $params, $item['name'], $item);
                    break;
            }
        }

        // prepare HTML
        $shouldMergeJs = Mage::getStoreConfigFlag('dev/js/merge_files');
        $shouldMergeCss = Mage::getStoreConfigFlag('dev/css/merge_css_files');
        $html   = '';
        foreach ($lines as $if => $items) {
            if (empty($items)) {
                continue;
            }
            if (!empty($if)) {
                $html .= '<!--[if '.$if.']>'."\n";
            }

            // static and skin css
            $html .= $this->_prepareStaticAndSkinElements('<link rel="stylesheet" type="text/css" href="%s"%s />' . "\n",
                empty($items['js_css']) ? array() : $items['js_css'],
                empty($items['skin_css']) ? array() : $items['skin_css'],
                $shouldMergeCss ? array(Mage::getDesign(), 'getMergedCssUrl') : null
            );

            // static and skin less
            $html .= $this->_prepareStaticAndSkinElements('<link rel="stylesheet/less" type="text/css" href="%s"%s />' . "\n",
                empty($items['js_less']) ? array() : $items['js_less'],
                empty($items['skin_less']) ? array() : $items['skin_less'],
                null
            );

            // static and skin javascripts
            $html .= $this->_prepareStaticAndSkinElements('<script type="text/javascript" src="%s"%s></script>' . "\n",
                empty($items['js']) ? array() : $items['js'],
                empty($items['skin_js']) ? array() : $items['skin_js'],
                $shouldMergeJs ? array(Mage::getDesign(), 'getMergedJsUrl') : null
            );

            // other stuff
            if (!empty($items['other'])) {
                $html .= $this->_prepareOtherHtmlHeadElements($items['other']) . "\n";
            }

            if (!empty($if)) {
                $html .= '<![endif]-->'."\n";
            }
        }
        return $html;
    }
}