Note'n'Cite
===========

Simple, lightweight footnotes and citations plug-in for WordPress.

Description and Usage
---------------------

Note'n'Cite is a plug-in designed to be the simplest footnote and citations
plug-in available for WordPress. It has no dependencies, does not use scripts,
and is designed to only use it's own styles when required.

The syntax is similar to that of Footnotes for WordPress, which offers a larger
and more interactive alternative. This is, in turn a combination of WordPress
'shortcode' and MediaWiki syntax for footnotes.

There are two basic functions: (foot)notes, and citations.
To add a footnote:

    [note]This is a footnote.[/note]

This will create a marker in the text, and add the footnote to a list of
footnotes at the end of the post's content. Links will also be created between
the entry in the list and the marker in the text.
```[ref]``` is also recognised for backwards compatibility with Footnotes for
WordPress.

Citations work in a similar way, but are expected to be links to other websites.
Therefore, the expect an href attribute.

    [cite href="http://github.com/javajawa/Note-n-Cite" /]

An entry will be created in the footnote list, however both the entry and the
marker will be links to the referenced website, set to open in a new window/tab.
If the `href` field is not supplied, the marker will contain the text 'citation
needed' and the link will function as if it were a note.
Any content in the citation will be included in the list entry.

If you define an ID for a footnote, you can also refer back to the same footnote
later on in the document. Names have to be unique per post for the plug-in to
work correctly; unnamed entries are given numbers, so it is advised that the
name field contains at least one letter to avoid any conflicts.

    [ref name="hello"]Hello world![/ref]
    [backref name="hello" /]

This functionality is also entirely backwards compatible with Footnotes for
WordPress.

Installation
------------

This plug-in is not currently available through the WordPress plug-in search.
However, installation is still simple.

First, you need to obtain a zip of the code. 'Stable' versions are available
under the downloads tab, or you can get a copy of any commit by clicking the 'zip'
button at the top of that commit's page.


There are two methods
- Through the WordPress admin interface, 'Add New', and upload the zip
- Uploading and extract the zip to a folder in ```wp-content/plugins```
(the name of the folder doesn't matter, but note-n-cite is recommended for
consistency reasons).

Note that the zips from commit pages will install to a different directory
per-commit. Attempting to run two different versions of this code at the same
time will cause errors. Old versions can be deleted from the Plugins page, or
manually from the folder ```wp-content/plugins```

Once the plug-in is installed, it have to be activated through the 'Plugins'
panel in the WordPress administration interface.

Configuration
-------------

Currently this plug-in does not have an options page.
There are, however, a number of options that you can set by editing the plug-in.

If you want notes and citations to be counted and listed separately, then change
the define at the top of note-n-cite.php to
    define('SEPARATE_CITE_NOTE', true);

The exact formatting of the output can be controlled from note-n-cite.css
(some knowledge of CSS recommended), and notes.php (which you are advised not to
edit unless you have reasonable experience of HTML and PHP).

Bug Reporting
-------------

Bug reports and feature requests are most welcome here on GitHub or anywhere you
happen to track me down to.

Don't forget to look in the current issue list first and, if you have edited the
code, make sure you supply your version.

License
-------

Copyright (c) 2012, Benedict Harcourt
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright
notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright
notice, this list of conditions and the following disclaimer in the
documentation and/or other materials provided with the distribution.
 * Neither the name of the Harcourt Programming nor the
names of its contributors may be used to endorse or promote products
derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL BENEDICT HARCOURT BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

