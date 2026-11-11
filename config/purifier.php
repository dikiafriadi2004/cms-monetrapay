<?php

/**
 * Ok, glad you are here
 * first we get a config instance, and set the settings
 * $config = HTMLPurifier_Config::createDefault();
 * $config->set('Core.Encoding', $this->config->get('purifier.encoding'));
 * $config->set('Cache.SerializerPath', $this->config->get('purifier.cachePath'));
 * if ( ! $this->config->get('purifier.finalize')) {
 *     $config->autoFinalize = false;
 * }
 * $config->loadArray($this->getConfig());
 *
 * You must NOT delete the default settings
 * anything in settings should be compacted with params that needed to instance HTMLPurifier_Config.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding' => 'UTF-8',
    'finalize' => true,
    'ignoreNonStrings' => false,
    'cachePath' => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings' => [
        'default' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
        ],
        // Profile for blog/page CMS content (TinyMCE output)
        'blog' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,p[style],br,hr,b,strong,i,em,u,s,sub,sup,mark,small,'
                .'a[href|title|target|rel],img[src|alt|title|width|height|loading],figure[class],figcaption,'
                .'ul,ol,li,dl,dt,dd,blockquote[cite],cite,q,abbr[title],kbd,code,pre,'
                .'table,thead,tbody,tfoot,tr,th[colspan|rowspan|scope],td[colspan|rowspan],caption,'
                .'div[class|style],span[class|style],iframe[src|width|height|frameborder|allowfullscreen|allow|title]',
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(?:https?:)?//(?:www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            'Attr.AllowedFrameTargets' => ['_blank', '_self'],
            'Attr.AllowedRel' => ['nofollow', 'noopener', 'noreferrer'],
            'CSS.AllowedProperties' => 'text-align,color,background-color,font-weight,font-style,text-decoration,padding-left',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => true,
            'AutoFormat.Linkify' => false,
            'Core.EscapeNonASCIICharacters' => false,
        ],
        // Profile for GrapesJS / page-builder content — richer tag/attribute set.
        // Scripts, on* handlers, and javascript: URIs are still blocked by HTMLPurifier.
        'page_builder' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' =>
                // Layout & structure
                'div[id|class|style],section[id|class|style],article[id|class|style],'
                .'header[id|class|style],footer[id|class|style],nav[id|class|style],'
                .'main[id|class|style],aside[id|class|style],'
                // Headings & text
                .'h1[id|class|style],h2[id|class|style],h3[id|class|style],'
                .'h4[id|class|style],h5[id|class|style],h6[id|class|style],'
                .'p[id|class|style],span[id|class|style],strong,em,b,i,u,s,br,hr,'
                .'small,mark,sub,sup,abbr[title],code,pre[class],kbd,'
                // Links & images
                .'a[href|title|target|rel|id|class|style],'
                .'img[src|alt|title|width|height|id|class|style|loading],'
                // Media
                .'figure[id|class|style],figcaption[id|class|style],'
                .'video[src|width|height|id|class|style|poster|controls|preload],'
                .'iframe[src|width|height|id|class|style|frameborder|allowfullscreen|allow|title],'
                // Lists
                .'ul[id|class|style],ol[id|class|style],li[id|class|style],'
                // Tables
                .'table[id|class|style],thead,tbody,tfoot,'
                .'tr[id|class|style],th[colspan|rowspan|scope|id|class|style],'
                .'td[colspan|rowspan|id|class|style],caption,'
                // Forms (read-only display; submit actions stripped)
                .'form[id|class|style],input[type|name|placeholder|id|class|style],'
                .'button[type|id|class|style],label[for|id|class|style],'
                .'select[name|id|class|style],option[value],'
                .'textarea[name|placeholder|id|class|style],'
                // Misc
                .'blockquote[cite|id|class|style],cite,q',
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(?:https?:)?//(?:www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|maps\.google\.com/maps|www\.google\.com/maps/)%',
            'Attr.AllowedFrameTargets' => ['_blank', '_self'],
            'Attr.AllowedRel' => ['nofollow', 'noopener', 'noreferrer'],
            // Allow broader CSS properties for layout/design
            'CSS.AllowedProperties' =>
                'color,background,background-color,background-image,background-position,background-size,'
                .'font,font-family,font-size,font-weight,font-style,line-height,letter-spacing,text-align,'
                .'text-decoration,text-transform,margin,margin-top,margin-right,margin-bottom,margin-left,'
                .'padding,padding-top,padding-right,padding-bottom,padding-left,'
                .'width,height,max-width,min-width,max-height,min-height,'
                .'border,border-radius,border-color,border-style,border-width,'
                .'display,flex,flex-direction,align-items,justify-content,gap,'
                .'position,top,right,bottom,left,z-index,overflow,'
                .'opacity,box-shadow,object-fit,object-position,'
                .'grid-template-columns,grid-column,grid-row',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
            'Core.EscapeNonASCIICharacters' => false,
        ],
        'test' => [
            'Attr.EnableID' => 'true',
        ],
        'youtube' => [
            'HTML.SafeIframe' => 'true',
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
        ],
        'custom_definition' => [
            'id' => 'html5-definitions',
            'rev' => 1,
            'debug' => false,
            'elements' => [
                // http://developers.whatwg.org/sections.html
                ['section', 'Block', 'Flow', 'Common'],
                ['nav',     'Block', 'Flow', 'Common'],
                ['article', 'Block', 'Flow', 'Common'],
                ['aside',   'Block', 'Flow', 'Common'],
                ['header',  'Block', 'Flow', 'Common'],
                ['footer',  'Block', 'Flow', 'Common'],

                // Content model actually excludes several tags, not modelled here
                ['address', 'Block', 'Flow', 'Common'],
                ['hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common'],

                // http://developers.whatwg.org/grouping-content.html
                ['figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common'],
                ['figcaption', 'Inline', 'Flow', 'Common'],

                // http://developers.whatwg.org/the-video-element.html#the-video-element
                ['video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', [
                    'src' => 'URI',
                    'type' => 'Text',
                    'width' => 'Length',
                    'height' => 'Length',
                    'poster' => 'URI',
                    'preload' => 'Enum#auto,metadata,none',
                    'controls' => 'Bool',
                ]],
                ['source', 'Block', 'Flow', 'Common', [
                    'src' => 'URI',
                    'type' => 'Text',
                ]],

                // http://developers.whatwg.org/text-level-semantics.html
                ['s',    'Inline', 'Inline', 'Common'],
                ['var',  'Inline', 'Inline', 'Common'],
                ['sub',  'Inline', 'Inline', 'Common'],
                ['sup',  'Inline', 'Inline', 'Common'],
                ['mark', 'Inline', 'Inline', 'Common'],
                ['wbr',  'Inline', 'Empty', 'Core'],

                // http://developers.whatwg.org/edits.html
                ['ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
                ['del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
            ],
            'attributes' => [
                ['iframe', 'allowfullscreen', 'Bool'],
                ['iframe', 'allow', 'Text'],
                ['img', 'loading', 'Enum#lazy,eager,auto'],
                ['table', 'height', 'Text'],
                ['td', 'border', 'Text'],
                ['th', 'border', 'Text'],
                ['tr', 'width', 'Text'],
                ['tr', 'height', 'Text'],
                ['tr', 'border', 'Text'],
            ],
        ],
        'custom_attributes' => [
            ['a', 'target', 'Enum#_blank,_self,_target,_top'],
        ],
        'custom_elements' => [
            ['u', 'Inline', 'Inline', 'Common'],
        ],
    ],

];
