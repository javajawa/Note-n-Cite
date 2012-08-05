<?php
/*
Plugin Name: Note'n'Cite
Plugin URI: github.com/javajawa/Note-n-Cite
Description: Lightweight and simple reference & footnotes plugin
Version: 0.3-SNAPSHOT
Author: Benedict Harcourt
Author URI: harcourtprogramming.co.uk
License: BSD 3-clause
*/

define('SEPARATE_CITE_NOTE', false);

class NoteAndCite
{
	function __construct()
	{
		add_shortcode('note', array(&$this, 'note'));
		add_shortcode('ref',  array(&$this, 'note'));
		add_shortcode('cite', array(&$this, 'cite'));
		add_shortcode('backref', array(&$this, 'backref'));

		add_filter('the_content', array(&$this, 'end_of_post'), 1000, 2);

		add_action('init', array(&$this, 'add_style'));
		add_action('init', array(&$this, 'on_new_post'));
	}

	function add_style()
	{
		wp_enqueue_style('note-n-cite', plugin_dir_url(__FILE__) . 'note-n-cite.css');
	}

	private $citations;
	private $notes;
	private $named_entries;
	private $post;

	public function on_new_post()
	{
		global $post;
		$this->post = $post->ID;
		$this->named_entries = array();
		$this->notes = array();
		if (SEPARATE_CITE_NOTE)
			$this->citations = array();
		else
			$this->citations = &$this->notes;
	}

	function note($atts, $content)
	{
		if ($content == null)
			return '';

		// Calculate bullet number + name
		$num = count($this->notes) + 1;
		// Generate IDs and add to array
		$noteId = $this->post.'-n-'.$num;
		$backId = 'to-' . $noteId;

		// Make sure inner tags have higher numbers
		$this->notes[$num] = null;

		// Allow any inside shortcodes to do their work, included nested notes.
		$content = do_shortcode($content);

		// Fill in the text of the note
		$entry = <<<EOF
			<li id="$noteId">
				<span class="note-marker">$num</span>
				<a class="note-return" href="#$backId">&#x2191;</a>
				$content
			</li>
EOF;

		$content = htmlentities(strip_tags($content));
		$link = '<a href="#'.$noteId.'" class="footnote" id="'.$backId.'" title="'.$content.'">'.$num.'</a>';

		$note = new Note($link, $entry);
		$this->notes[$num] = &$note;

		if (array_key_exists('name', $atts))
			$this->named_entries[$atts['name']] = &$note;

		return $link;
	}

	function cite($atts, $content = null)
	{
		// Calculate bullet number + name
		$num = count($this->citations) + 1;
		// Generate IDs
		$noteId = $this->post . '-c-' . $num;
		$backId = 'to-' . $noteId;

		if ($content === null)
			$content = '';

		if (array_key_exists('href', $atts))
		{
			$href = $atts['href'];
			$link = <<<EOF
<a rel="cite" href="$href" class="citation" id="$backId" target="_blank">$num</a>
EOF;
			$entry = <<<EOF
				<li id="$noteId">
					<span class="note-marker">$num</span>
					<a class="note-return" href="#$backId">&#x2191;</a>
					$content
					<a rel="cite" href="$href" target="_blank">$href</a>
				</li>
EOF;
		}
		else
		{
			$link = <<<EOF
<a href="#$noteId" class="citation" id="$backId">Citation Needed</a>
EOF;
			$entry = <<<EOF
				<li id="$noteId">
					<span class="note-marker">$num</span>
					<a class="note-return" href="#$backId">&#x2191;</a>
					[Citation Needed] $content
				</li>
EOF;
		}

		$note = new Note($link, $entry);
		$this->citations[$num] = &$note;

		if (array_key_exists('name', $atts))
			$this->named_entries[$atts['name']] = &$note;

		return $link;
	}

	function backref($atts = array())
	{
		if (array_key_exists('name', $atts) && array_key_exists($atts['name'], $this->named_entries))
			return $this->named_entries[$atts['name']]->getLink();
		else
			return '';
	}

	function note_list()
	{
		if (count($this->notes) == 0)
			return '';

		$ret = '<ol class="footnotes">' . PHP_EOL;
		foreach ($this->notes as $note)
		{
			$ret .= "\t" . $note->getEntry() . PHP_EOL;
		}
		$ret .= '</ol>' . PHP_EOL;

		return $ret;
	}

	function cite_list()
	{
		if (count($this->citations) == 0)
			return '';

		$ret = '<ol class="citations">' . PHP_EOL;
		foreach ($this->citations as $note)
		{
			$ret .= "\t" . $note->getEntry() . PHP_EOL;
		}
		$ret .= '</ol>' . PHP_EOL;

		return $ret;
	}

	function end_of_post($content)
	{
		$content .= $this->note_list();
		if (SEPARATE_CITE_NOTE)
			$content .= $this->cite_list();

		$this->on_new_post();

		return $content;
	}
}

class Note
{
	private $link;
	private $entry;

	public function __construct($link, $entry)
	{
		$this->link = $link;
		$this->entry = $entry;
	}

	public function getLink()
	{
		return $this->link;
	}

	public function getEntry()
	{
		return $this->entry;
	}
}

new NoteAndCite();
