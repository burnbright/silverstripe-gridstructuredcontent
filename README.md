# SilverStripe Grid Structured Content Module

The layout-with-tables killer.

## Author

 * Jeremy Shipman (Jedateach) <jeremy@burnbright.net>

## Why

**Problem:** structuring content beyond single columns.

**Existing solutions:**
 
 * pre-define templates & content positions
 * use tables within TinyMCE Content editor
 * define content in seperate DataObjects, eg [Page Elements Module](http://page-elements.com)
 
**New solution:** use the CSS grids we've come to know and love

This can be done by splitting up 'Content' field with a short delimiter string, and also storing a (sub)grid structure.
The grid strucutre could be predefined by a designer, or edited by the user. This means defining the appropriate nesting
of rows, columns, and width each column spans.

The same TinyMCE editor interface can be used, with the only difference being that you choose what part of the grid
you are working on. On save, the seperate grid sections are concatenated into the 'Content' db field, and are delimited
by a front-end invisible string.

When rendering on the front-end, the defined grid structure is traversed (depth first), dropping the delimited Content
into the appropriate areas.

This idea relies on the fact that most grid systems are the same. Most have:

 * Predefined number of columns
 * HTML structure & CSS classes for defining grid layout
 
 For example, here is the [bootstrap structure](http://twitter.github.com/bootstrap/scaffolding.html#gridSystem)
 
```html
<div class="row">
    <div class="span4">...</div>
    <div class="span8">...</div>
</div>
```

converting this to a SS-generic approach:

```
<div class="row">
	<% loop Columns %>
		<div class="span{$Width}">
			$Content
		</div>
	<% end_loop %>
</div>
```
 
The minor differences between grid systems should be handled by using a slightly different row template.

## Benefits:

 * Grid system agnostic: it should would work with different CSS grid systems.
 * Un-obtrusive: it doesn't require any new data objects, or fields.
 * Clever: all content is stored in 'Content' db field, therefore taking full advantage of history, rollback etc.
 * Graceful: the 'Content' field could still be displayed in a single column format with $Content, 
 	because delimiters will be hidden. This also means content summaries can still be used.

# Research

### List of CSS Grid Systems/Frameworks

 * [34](http://34grid.com/)
 * [960](http://960.gs)
 * [978](http://978.gs)
 * [1140](http://cssgrid.net/)
 * [blueprint](http://www.blueprintcss.org)
 * [bootstrap](http://twitter.github.com/bootstrap)
 * [columnal](http://www.columnal.com/)
 * [foundation](http://foundation.zurb.com)
 * [golden](http://goldengridsystem.com/)
 * [responsive](http://www.responsivegridsystem.com/)
 * [responsive 2](http://responsive.gs/)
 * [semantic](http://semantic.gs/)
 * [unsemantic](http://unsemantic.com)
 * [zen](http://zengrids.com/)

### Javascript Grid Layout Editors

 * http://headwaythemes.com/features/headway-grid/
 * https://gridsetapp.com/features/
 * http://www.3x4grid.com/
 * http://gridster.net/