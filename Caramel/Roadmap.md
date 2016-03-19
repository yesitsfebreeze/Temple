# variable parsing
==================

the main problem here is to determent when the variable is within a function or an text block
we need to parse them differently, one with an echo (text/function)
and the other without an echo

some initial ideas

make an escape pattern like e(variable) to echo them and v(variable) to use them plain

the next problem is that i want to have a clean function syntax,
therefore it's kinda bad to have so much escapes within those function statements
so we have to differ further between functions and other stuff

the best solution would be to have a simple global identifier and handle it dynamically
within the plugins


# doctype plugin
==================

I have to research which doctypes we can offer etc

# library
==================

create a preset library for meta tags
and blocks that will occur often

# NAMESPACE

namespace mine
	this is namespaces with myspace
	this also
	namespace theirs
		this has theirs as namespace



# EXTENDS

the extend statement links the current file to the selected one and makes blocks and variables available within the current file

## extends
if no file is given we search the parent templates until we find the same file in those folders and then extend it

## extends subfolder/file
if a relative path is given we search the templates folder recursively for that file until we find one and then extend it

## extends /folder/subfolder/file
if a absolute path is given we search for the file from our root template recursively until we find one and then extend it


# block

Blocks are a main feature in caramel.
They are sections which can be overwritten by other files.
Therefore you will have a modular template which can be adjusted on each view if needed.

You can warp, replace, append and prepend blocks as many times as you wish.


## block myfirstblock
this creates a block

## block prepend myfirstblock
	this gets inserted before the block „myfirstblock“

## block append myfirstblock
this gets inserted after the block „myfirstblock“

## block replace myfirstblock 
this replaces the whole content of the „myfirstblock“ block

## block wrap myfirstblock 
the content of this block will be wrapped around the block


# PLAIN
this function parses its children as plain text

# Filter

Filters are used to inject other languages into Caramel
Something like Markdown
so we could write a wrapper function that parses the content of a function with markdown and puts it into the template

a prime example would be the php filter

php
	this code will be rendered as is


# Default functions

## for
for this as key,value
for this as value

## if

if that == true
	do this

if that == true
	do this
else
	do that


if that == true
	do this
else if that == false
	do something else
else
	do that

## custom functions
you can also write custom functions like this

function test param1 param2
	php
		echo param1
		echo param2


## plugin space
    a wrapper for plugins and the order of initialisation!

## snippets
    per ini file
    
TODO: variables need namespacing/scoping
