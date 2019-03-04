<?php

namespace Javer\SphinxBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;
use SqlFormatter;

/**
 * This class contains the needed function in order to do the query highlighting
 */
class PrettyQueryExtension extends Twig_Extension
{
    /**
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('sphinx_pretty_query', [$this, 'formatQuery'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Formats highlights the given SQL statement.
     *
     * @param  string $sql
     *
     * @return string
     */
    public function formatQuery($sql)
    {
        SqlFormatter::$pre_attributes            = 'class="highlight highlight-sql"';
        SqlFormatter::$quote_attributes          = 'class="string"';
        SqlFormatter::$backtick_quote_attributes = 'class="string"';
        SqlFormatter::$reserved_attributes       = 'class="keyword"';
        SqlFormatter::$boundary_attributes       = 'class="symbol"';
        SqlFormatter::$number_attributes         = 'class="number"';
        SqlFormatter::$word_attributes           = 'class="word"';
        SqlFormatter::$error_attributes          = 'class="error"';
        SqlFormatter::$comment_attributes        = 'class="comment"';
        SqlFormatter::$variable_attributes       = 'class="variable"';

        $html = SqlFormatter::format($sql);
        $html = preg_replace('/<pre class="(.*)">([^"]*+)<\/pre>/Us', '<div class="\1"><pre>\2</pre></div>', $html);

        return $html;
    }
}

