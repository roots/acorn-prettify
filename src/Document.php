<?php

namespace Roots\AcornPrettify;

use DOMDocument;
use DOMNodeList;
use DOMXPath;

class Document
{
    /**
     * The DOMDocument instance.
     */
    protected DOMDocument $document;

    /**
     * Initialize the Document instance.
     */
    public function __construct(string $html)
    {
        $this->document = new DOMDocument(encoding: 'UTF-8');

        $this->document->loadHTML(
            '<html><body>'.$html.'</body></html>',
            LIBXML_HTML_NODEFDTD | LIBXML_NOXMLDECL | LIBXML_NOWARNING | LIBXML_NOERROR
        );
    }

    /**
     * Make a new instance of the Document.
     */
    public static function make(string $html): self
    {
        return new static($html);
    }

    /**
     * Loop through each node in the document and execute the provided callback.
     */
    public function each(callable $callback): self
    {
        foreach ($this->xpath('//*') as $node) {
            $callback($node);
        }

        return $this;
    }

    /**
     * Evaluate the given XPath expression.
     */
    public function xpath(string $expression): DOMNodeList
    {
        return (new DOMXPath($this->document))->query($expression);
    }

    /**
     * Get the saved document HTML.
     */
    public function html(): string
    {
        $html = $this->document->saveHTML();

        // Strip the html/body wrapper added during parsing
        $html = preg_replace('~^.*?<body>|</body>.*$~si', '', $html);

        return trim($html);
    }

    /**
     * Call the given method on the root document.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->document->{$name}(...$arguments);
    }

    /**
     * Get the given property from the root document.
     */
    public function __get(string $name): mixed
    {
        return $this->document->{$name};
    }
}
