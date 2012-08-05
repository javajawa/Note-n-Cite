<?php
/*
Plugin Name: Note'n'Cite
Plugin URI: github.com/javajawa/Note-n-Cite
Description: Lightweight and simply reference & footnotes plugin
Version: 0.2-SNAPSOHT
Author: Benedict Harcourt
Author URI: harcourtprogramming.co.uk
License: BSD 3-clause
*/

class NoteAndCite
{
	function NoteAndCite()
	{
		add_shortcode('note', array(&$this, 'note'));
		add_shortcode('ref',  array(&$this, 'note'));
		add_shortcode('cite', array(&$this, 'cite'));
		add_shortcode('backref', array(&$this, 'backref'));
		add_filter('the_content', array(&$this, 'note_list'), 1000, 2);
		add_action('init', array(&$this, 'add_style'));
	}

	function add_style()
	{
		wp_enqueue_style('note-n-cite', plugin_dir_url(__FILE__) . 'note-n-cite.css');
	}

	var $notes = array();
	var $entries = array();

	function note($atts, $content)
	{
		if ($content == null)
			return '';

		global $post;

		// Create array for this post
		if (!array_key_exists($post->ID, $this->entries))
			$this->entries[$post->ID] = array();

		// Select array
		$post_arr = &$this->entries[$post->ID];

		// Calculate bullet number + name
		$num = count($post_arr) + 1;
		if (array_key_exists('name', $atts))
			$name = $atts['name'];
		else
			$name = $num;

		// Generate IDs and add to array
		$noteId = $post->ID.'-n-'.$name;
		$backId = 'to-' . $noteId;
		$post_arr[$noteId] = $num;

		// Create the text node for this note, and mark it in the accumulator
		$note = '';
		$this->notes[] = &$note;

		// Allow any inside shortcodes to do their work, included nested notes.
		$content = do_shortcode($content);

		// Fill in the text of the note
		$note = <<<EOF
			<li id="$noteId">
				<span class="note-marker">$num</span>
				<a class="note-return" href="#$backId">&#x2191;</a>
				$content
			</li>
EOF;

		$content = htmlentities(strip_tags($content));
		return '<a href="#'.$noteId.'" class="footnote" id="'.$backId.'" title="'.$content.'">'.$num.'</a>';
	}

	function cite($atts)
	{
		global $post;

		// Create array for this post
		if (!array_key_exists($post->ID, $this->entries))
			$this->entries[$post->ID] = array();

		// Select array
		$post_arr = &$this->entries[$post->ID];

		// Calculate bullet number + name
		$num = count($post_arr) + 1;
		if (array_key_exists('name', $atts))
			$name = $atts['name'];
		else
			$name = $num;

		// Generate IDs and add to array
		$noteId = $post->ID . '-n-' . $name;
		$backId = 'to-' . $noteId;
		$href = array_key_exists('href', $atts) ? $atts['href'] : 'Citation Needed';
		$post_arr[$noteId] = $num;

		// Fill in the text of the note
		$this->notes[] = <<<EOF
			<li id="$noteId">
				<span class="note-marker">$num</span>
				<a class="note-return" href="#$backId">&#x2191;</a>
				<a rel="cite" href="$href" target="_blank">$href</a>
			</li>
EOF;

		if (array_key_exists('href', $atts))
			return '<a rel="cite" href="'.$href.'" class="citation" id="'.$backId.'" target="_blank">'.$num.'</a>';
		else
			return '<a href="#'.$noteId.'" class="citation" id="'.$backId.'">Citation Needed</a>';
	}

	function backref($atts = array())
	{
		global $post;

		if (!array_key_exists('name', $atts))
			return '';

		$noteId = $post->ID . '-n-' . $atts['name'];
		$num = $this->bullets[$post->ID][$noteId];

		return '<a href="#'.$noteId.'" class="footnote">'.$num.'</a>';

	}

	function note_list($content)
	{
		if (count($this->notes) > 0)
		{
			$content .= '<ol class="footnotes">' .
				implode("\n\t", $this->notes) . '</ol>' . PHP_EOL;

			$this->notes = array();
		}
		return $content;
	}
}

new NoteAndCite();

