<?php

/**
 * Holzweg Boomstrap
 *
 * @category    design
 * @package     hwboomstrap
 * @copyright   Copyright (c) 2012 Holzweg e-commerce solutions (http://www.holzweg.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 */
class Holzweg_Boomstrap_Catalog_Block_Navigation extends Mage_Catalog_Block_Navigation
{

    /**
     * Render categories menu in HTML
     *
     * @param int Level number for list item class to start from
     * @param string Extra class of outermost list items
     * @param boolean Whether or not to process and display only one level of navigation
     * @param string If specified wraps children list in div with this class
     * @return string
     */
    public function renderCategoriesMenuHtml( $level = 0, $outermostItemClass = '', $firstLevelOnly = false, $childrenWrapClass = '' )
    {
        $activeCategories = array();
        foreach ($this->getStoreCategories() as $child)
        {
            if ($child->getIsActive())
            {
                $activeCategories[]       = $child;
            }
        }
        $activeCategoriesCount    = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount)
        {
            return '';
        }

        $html = '';
        $j    = 0;

        foreach ($activeCategories as $category)
        {
            $html .= $this->_renderCategoryMenuItemHtml(
                    $category, $level, $firstLevelOnly, ($j == $activeCategoriesCount - 1), ($j == 0), true, $outermostItemClass, $childrenWrapClass, true
            );
            $j++;
        }

        return $html;

    }

    /**
     * Render category to html
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int Nesting level number
     * @param boolean Whether or not to process and display only one level of navigation
     * @param boolean Whether ot not this item is last, affects list item class
     * @param boolean Whether ot not this item is first, affects list item class
     * @param boolean Whether ot not this item is outermost, affects list item class
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @param boolean Whether ot not to add on* attributes to list item
     * @return string
     */
    protected function _renderCategoryMenuItemHtml( $category, $level = 0, $firstLevelOnly = false, $isLast = false, $isFirst = false, $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false )
    {
        if (!$category->getIsActive())
        {
            return '';
        }
        $html = array();

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled())
        {
            $children      = (array) $category->getChildrenNodes();
            $childrenCount = count($children);
        }
        else
        {
            $children      = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren   = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child)
        {
            if ($child->getIsActive())
            {
                $activeChildren[]    = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren   = ($activeChildrenCount > 0);

        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        if ($this->isCategoryActive($category))
        {
            $classes[]   = 'active';
        }
        $linkClasses = array();
        if ($isOutermost && $outermostItemClass)
        {
            $classes[]     = $outermostItemClass;
            $linkClasses[] = $outermostItemClass;
        }

        if ($hasActiveChildren)
        {
            $classes[] = 'parent';
        }

        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0)
        {
            $attributes['class'] = implode(' ', $classes);
        }

        // assemble list item with attributes
        $htmlLi = '<li';
        foreach ($attributes as $attrName => $attrValue)
        {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
        }
        $htmlLi .= '>';
        $html[] = $htmlLi;

        $html[] = '<a href="' . $this->getCategoryUrl($category) . '" class="' . implode(" ", $linkClasses) . '">';
        $html[] = $this->escapeHtml($category->getName());

        $html[] = '</a>';

        // render children
        if (!$firstLevelOnly)
        {
            $htmlChildren = '';
            $j            = 0;
            foreach ($activeChildren as $child)
            {
                $htmlChildren .= $this->_renderCategoryMenuItemHtml(
                        $child, ($level + 1), false, ($j == $activeChildrenCount - 1), ($j == 0), false, $outermostItemClass, $childrenWrapClass, $noEventAttributes
                );
                $j++;
            }
            if (!empty($htmlChildren))
            {
                if ($childrenWrapClass)
                {
                    $html[] = '<div class="' . $childrenWrapClass . '">';
                }
                $html[] = '<ul class="level' . $level . '">';
                $html[] = $htmlChildren;
                $html[] = '</ul>';
                if ($childrenWrapClass)
                {
                    $html[] = '</div>';
                }
            }
        }

        $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;

    }

}
