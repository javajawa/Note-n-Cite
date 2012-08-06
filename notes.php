<?php

class Note
{
	protected $postId;
	protected $num;
	protected $content;
	protected $returns = array();

	public function __construct($postId, $num, $content)
	{
		$this->postId = $postId;
		$this->num = $num;
		$this->content = $content;
	}

	public function getEntryId()
	{
		return $this->postId . '-n-' . $this->num;
	}

	public function getLink()
	{
		$return = 'to-' . $this->getEntryId() . '-' . count($this->returns);
		$this->returns[] = $return;

		return sprintf(
			'<a href="#%s" class="footnote" id="%s" title="%s">%s</a>',
			$this->getEntryId(), $return, htmlentities(strip_tags($this->content)), $this->num
		);
	}

	public function getEntry()
	{
		$entry = '<li id="' . $this->getEntryId() . '">' . PHP_EOL;
		$entry .= '<span class="note-marker">' . $this->num . '</span>' . PHP_EOL;

		foreach ($this->returns as $return)
		{
			$entry .= '<a class="note-return" href="#' . $return . '">&#x2191;</a>' . PHP_EOL;
		}

		$entry .= $this->content . PHP_EOL;
		$entry .= '</li>';

		return $entry;
	}

}

class Citation extends Note
{
	protected $href;

	protected static $blankText = 'Citation Needed';

	public function __construct($postId, $num, $content, $href)
	{
		parent::__construct($postId, $num, $content);
		$this->href = $href;
	}

	public function getEntryId()
	{
		return $this->postId . '-c-' . $this->num;
	}

	public function getLink()
	{
		$return = 'to-' . $this->getEntryId() . '-' . count($this->returns);
		$this->returns[] = $return;

		if ($this->href == null)
		{
			return sprintf(
				'<a href="#%s" class="citation" id="%s" target="_blank">%s</a>',
				$this->getEntryId(), $return, self::$blankText
			);
		}
		else
		{
			return sprintf(
				'<a href="%s" class="citation" id="%s" target="_blank">%s</a>',
				$this->href, $return, $this->num
			);
		}
	}

	public function getEntry()
	{
		$entry = '<li id="' . $this->getEntryId() . '">' . PHP_EOL;
		$entry .= '<span class="note-marker">' . $this->num . '</span>' . PHP_EOL;

		foreach ($this->returns as $return)
		{
			$entry .= '<a class="note-return" href="#' . $return . '">&#x2191;</a>' . PHP_EOL;
		}

		$entry .= $this->content . PHP_EOL;

		if ($this->href == null)
			$entry .= self::$blankText . PHP_EOL;
		else
			$entry .= '<a href="' . $this->href . '" target="_blank">' . $this->href . '</a>';

		$entry .= '</li>';

		return $entry;
	}
}
