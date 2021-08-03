<?php
/**
 * DokuWiki Plugin currentlyreading (Syntax Component)
 *
 * Displays a "Currently Reading" box with an image of the book cover and a link to the page.
 * The image is pulled from the first image of the page and it is assumed that the page is about the story.
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Charles Fewlass <fewlass@suddenlink.net>
 */

// must be run within DokuWiki
if(!defined('DOKU_INC')) die();

/**
 * Class syntax_plugin_currentlyreading
 */
class syntax_plugin_currentlyreading extends \dokuwiki\Extension\SyntaxPlugin {
    public function getType() { return 'substition'; }
    public function getSort() { return 302; }
    public function getPType(){ return 'block'; }

    /**
     * regex pattern for lexer to seek
     */
    public function connectTo($mode) {
        // Looking to match {{reading>[pageid]}}
        $pattern = '\{\{reading\b>[[:blank:]]??[[:alnum:]]{1}(?:[[:alnum:]]|[[:blank:]]|\.|,|-|_|:)+?[[:alnum:]]{1}(?:\?[0-9]+?)*?[[:blank:]]??\}\}';
        $this->Lexer->addSpecialPattern($pattern, $mode, 'plugin_currentlyreading');
    }

    /**
     * handle matches from lexer
     *
     * @param   string  $match  Match from lexer
     * @return  string  $html   HTML string to render
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // Let's get the $pagename through its first phase.
        $pagename = substr($match, 10, -2); // Remove the markup to reveal the pagename.
        $pagename = hsc($pagename); // Clean up html entities.
        $pagename = preg_replace('/\s\s+/', ' ', $pagename); // Eliminate excessive spaces to single space, but keep leading and trailing.

        // What is the float?
        $left = $right = $center = false;
        if (substr($pagename, 0, 1) == ' ') $left = true;
        if (substr($pagename, -1, 1) == ' ') $right = true;
        if (($left == false) && ($right == false)) {
            $float = '';
        } elseif ($left && $right) {
            $float = ' mediacenter';
        } else {
            if ($left) $float = ' mediaright';
            if ($right) $float = ' medialeft';
        }

        // What will be the width?
        $width = null;
        if ($spos = strpos($pagename, '?')) {
            $width = intval(substr($pagename, $spos+1)); // The user specified a width in the markup.
            $pagename = substr($pagename, 0, -(strlen($pagename)-$spos)); // Strip the width declaration off of the $pagename.
        } else {
            $width = $this->getConf('width'); // Get the width from the configuration settings.
        }

        // $pagename's second phase: create a pagename ID that we can look up.
        $title = trim($pagename); // Save the original for a possible $title.
        $pagename = trim($pagename);
        $pagename = strtolower($pagename);
        $pagename = strtr($pagename, ' ', '');

        // Enquiring minds want to know: does the page exist?
        $pageexists = false;
        if (page_exists($pagename)) $pageexists = true;

        // Is there a preferred title from a wiki page or do we use the input.
        if ($pageexists) {
            $title = p_get_metadata($pagename, 'title', METADATA_DONT_RENDER);
        } else {
            $title = strtr(noNS($title), '_', ' ');
        }

        // Create the link.
        $link = wl($pagename);

        // Get the image if it exists and create the image tag.
        $image = (p_get_metadata($pagename, 'relation firstimage', METADATA_DONT_RENDER));

        if ($image) {
            $imagetag = '<img class="plugin_currentlyreading" style="max-width: '.$width.'px;" src="'.ml($image).'" alt="'.$title.'" />';
        } else {
            $imagetag = null;
        }

        // What heading to use?
        if ($this->getConf('alternative')) { // Use an alternative heading from the config settings or from the language files.
            $heading = hsc($this->getConf('alternative'));
        } else $heading = $this->getLang('currentlyreading');

        // Putting HTML altogether.
        $html = '<div class="plugin_currentlyreading'.$float.'" style="max-width: '.$width.'px;"><span class="plugin_currentlyreading">'.$heading.'</span><br />'.
                '<a class="plugin_currentlyreading" href="'.$link.'">'.$title.'</a><br />'.
                $imagetag.'</div>';

        return $html;
    }

    /**
     * Renders $html
     *
     * @param   string   $html   HTML to render
     * @return  boolean
     */
    public function render($mode, Doku_Renderer $renderer, $html) {
        if ($html === false) return false;
        if ($mode !== 'xhtml') return false;

        $renderer->doc .= $html;

        return true;
    }
}
