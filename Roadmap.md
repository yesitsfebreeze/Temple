# doctype

    have to research here


# STATIC

if we parse the template and have a static block, the content of that block will be parsed as it would be displayed

example:

that = true
static
	if that == true
		say hello

output:

say hello

note: as of now you can’t use blocks in static blocks, since they are only for fast rendering of static content


# dynamic

if we parse the template and have a dynamic block, the content of that block will be parsed with variables an functions

example:

that = true
static
	if that == true
		say hello

output:

<?php if ($that == true) { ?>
	say hello
<?php } ?>



# MIXING STATIC AND DYNAMIC

example:

dynamic
	if that == true
		static
			each [1,2,3] as number
				<div class=„el-(_number_)“></div>

output:
	<?php if ($that == true) { ?>
		<div class=„el-1“></div>
		<div class=„el-2“></div>
		<div class=„el-3“></div>
	<?php } ?>



# NAMESPACE

namespace mine
	this is namespaces with myspace
	this also
	namespace theirs
		this has theirs as namespace



# EXTENDS

- extends

if no file is given we search the parent templates until we find the same file in those folders and then extend it


- extends subfolder/file

if a relative path is given we search the templates folder recursively for that file until we find one and then extend it


- extends /folder/subfolder/file

if a absolute path is given we search for the file from our root template recursively until we find one and then extend it



#BLOCKS

append
prepend
replace/block
wrap

# PLAIN

this function parses its children as plain text



# IF
	
if that == this
	true



# IF ELSE

if that == this
	true
else
	false



# IF ELSE IF

if that == this
	true
else if they == those
		true
else
	true

TODO: add default setting for cache dir

TODO: implement dynamic cache functionality

TODO: implement imports
TODO: implement static/dynamic sections
TODO: implement blocks

TODO: modifiers ?

TODO: namespaceing idea:

    TODO: div test
        TODO: namespace hallo
            TODO: everything that’s defined her and could have a namespace is prefixed
        TODO: namespace global
            TODO: same here but global space
    TODO: file namespace here


TODO: functions:
    TODO: - for
    TODO: - if
    TODO: - else if
    TODO: - counter
    TODO: - each bla as k,v ( each bla as v )
    TODO: - function name(param) -> php -> definitely needs name spacing so it should be useful to implement a namespace
    TODO: - php



