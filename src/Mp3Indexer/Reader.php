<?php
class Mp3Indexer_Reader
{
	public function __construct (
		sfEventDispatcher $dispatcher,
		sfEvent $linter
	) {
		$this->_dispatcher = $dispatcher;
		$this->_dispatcher->connect('mp3scan.file', array($this, 'read'));
		$this->_linter = $linter;
	}

	public function read($event)
	{
		$file = $event['file'];

		// lint them files
		$event = clone $this->_linter;
		$this->_dispatcher->filter($event, $file);
		$file = $event->getReturnValue();

var_dump(id3_get_tag($file->getPathname(), ID3_V2_4));
	}
}
