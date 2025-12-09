<?php

namespace Roots\AcornPrettify;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Illuminate\Support\Str;

class Document
{
    /**
     * The DOMDocument instance.
     */
    protected DOMDocument $document;

    /**
     * The XML encoding tag.
     */
    protected string $encoding = '<?xml encoding="UTF-8">';

    /**
     * Initialize the Document instance.
     */
    public function __construct(string $html)
    {
        $this->document = new DOMDocument;

        $this->suppress();

        $this->document->loadHTML(
            Str::start($html, $this->encoding),
            \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD
        );

        $this->clear();
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

        // Remove XML declaration/comment that libxml2 may add
        // Handles both <?xml encoding="UTF-8"> and any comments libxml2 adds
        $html = preg_replace('/^<\?xml[^>]*>\s*/i', '', $html);
        // Use non-greedy match to handle comments containing > characters
        $html = preg_replace('/^<!--.*?-->\s*/s', '', $html);

        return trim($html);
    }

    /**
     * Suppress the XML errors.
     */
    protected function suppress(): self
    {
        libxml_use_internal_errors(true);

        return $this;
    }

    /**
     * Clear the XML errors.
     */
    protected function clear(): self
    {
        libxml_clear_errors();

        return $this;
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
