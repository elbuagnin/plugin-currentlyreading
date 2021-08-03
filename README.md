# plugin-currentlyreading

This is a plugin for the wiki software, DokuWiki.

Upon a call by wiki markup, it outputs and html block that includes the text, "Currently Reading,"
followed by the title of the story or book, followed by an image of the book cover. The title is
linked to a corresponding page in the wiki.

Syntax: {{reading>title[?width]}}

@param  title   required  string  pagename or namespace:pagename in standard DokuWiki format
@param  width   optional  int   width of image in pixels
  
Additionally, the box can float using the same syntax for images in DokuWiki by inserting a space 
before the title (right float), after the parameters (left float), or both (center).

Examples:
  {{reading> conjurewife}}    // floats right
  {{reading>conjurewife }}    // floats left
  {{reading> conjurewife }}   // center
  {{reading>conjurewife}}     // none
  
  {{reading>fiction:shortstories:smokeghost}}   // the page smokeghost in the namespace fiction:shortstories
  
  {{reading>leantimesinlankhmar?300}}           // overrides the width in the configuration settings and sets it to 300 pixels
  
  
