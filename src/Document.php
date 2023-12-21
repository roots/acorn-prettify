<?php

namespace Roots\AcornPretty;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Exception;
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
     * Create a new DOM instance.
     *
     * @return void
     */
    public function __construct(string $html)
    {
        $this->document = new DOMDocument();

        try {
            $this->document->loadHTML(
                Str::start($html, $this->encoding),
                \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD
            );
        } catch (Exception) {
            //
        }
    }

    /**
     * Make a new DOM instance.
     */
    public static function make(string $html): self
    {
        return new static($html);
    }

    /**
     * Executes callback on each DOMElement.
     *
     * @param  callable  $callback
     */
    public function each($callback): self
    {
        foreach ($this->xpath('//*') as $node) {
            $callback($node);
        }

        return $this;
    }

    /**
     * Evaluates the given XPath expression.
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
        return trim(substr($this->document->saveHTML(), 23));
    }

    /**
     * Call methods on the root document.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->document->{$name}(...$arguments);
    }

    /**
     * Get properties from the root document.
     */
    public function __get(string $name): mixed
    {
        return $this->document->{$name};
    }
}
