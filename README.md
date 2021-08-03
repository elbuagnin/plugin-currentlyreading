# plugin-currentlyreading

## This is a plugin for the wiki software, DokuWiki.
https://www.dokuwiki.org/plugins

Upon a call by wiki markdown, it outputs an html block that includes the text, "Currently Reading,"
followed by the title of the story or book, followed by an image of the book cover. The title is
linked to a corresponding page in the wiki.

### Syntax
`{{reading>pagename[?width]}}`

**@param**  `pagename`   *required  string* -  Pagename or namespace:pagename in standard DokuWiki format \
**@param**  `width`   *optional  integer* -  Width of image in pixels
  
Additionally, the box can float using the same syntax for images used by DokuWiki by inserting a space 
before the title (right float), after the parameters (left float), or both (center).

### Examples
`{{reading> conjurewife}}`    - Floats right \
`{{reading>conjurewife }}`    - Floats left \
`{{reading> conjurewife }}`   - Center \
`{{reading>conjurewife}}`     - None

`{{reading>fiction:shortstories:smokeghost}}`   - The page smokeghost in the namespace fiction:shortstories

`{{reading>leantimesinlankhmar?300}}`           - Overrides the width in the configuration settings and sets it to 300 pixels
  
### Functionality
#### Title
The title shown is pulled from the page's metadata: `p_get_metadata($pageid, 'title')`

#### Image
The image shown is pulled from the page's metadata: `p_get_metadata($pageid, 'relation firstimage')`

#### Page Does Not Exist
If the wiki page does not exist, the plugin will still render a box with a heading and title linked to a non-existing wiki page.
The plugin will use the pagename for the title and will keep any capitalization and spaces used. The link will be lowercase with spaces eliminated.

### Configuration
**@param**  *Width* - The default width of the box. \
**@param**  *Alternate Heading* - An alternate heading to be used in place of the built in heading of "Currently Reading."
If it is left empty, the plugin will use the default heading.

### Formatting
A .css file in the plugin directory can be edited to change the formatting of the box.
