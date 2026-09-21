<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;

/**
 * Strips the genuinely dangerous parts out of HTML produced by the mail
 * compose editor (TinyMCE) and out of received messages' body_html before
 * it's re-injected into a reply's quote block. Not a full HTML allowlist
 * sanitizer (e.g. mews/purifier) - the editor itself only ever produces
 * formatting markup (no script-insertion UI is exposed), and received
 * bodies are already shown read-only inside a sandboxed, script-disabled
 * iframe (see admin/mail/show.blade.php). This is defense-in-depth on top
 * of that: script/style/iframe/object/embed/form elements, "on*" event
 * handler attributes, and javascript: URLs are removed either way.
 */
class HtmlSanitizer
{
    private const DISALLOWED_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'form',
        'input', 'button', 'select', 'textarea', 'link', 'meta', 'base', 'svg',
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $dom = new DOMDocument();

        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>'.$html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        foreach (self::DISALLOWED_TAGS as $tag) {
            foreach (iterator_to_array($dom->getElementsByTagName($tag)) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        $xpath = new DOMXPath($dom);

        /** @var DOMElement $element */
        foreach (iterator_to_array($xpath->query('//*')) as $element) {
            foreach (iterator_to_array($element->attributes ?? []) as $attribute) {
                $name = strtolower($attribute->nodeName);

                if (str_starts_with($name, 'on')) {
                    $element->removeAttribute($attribute->nodeName);

                    continue;
                }

                if (in_array($name, ['href', 'src'], true) && preg_match('/^\s*javascript:/i', $attribute->nodeValue)) {
                    $element->removeAttribute($attribute->nodeName);
                }
            }
        }

        $output = '';
        foreach (iterator_to_array($dom->childNodes) as $node) {
            // Skip the leading XML encoding processing instruction added above
            // purely to force UTF-8 parsing - it isn't part of the content.
            if ($node->nodeType === XML_PI_NODE) {
                continue;
            }

            $output .= $dom->saveHTML($node);
        }

        return $output;
    }
}
