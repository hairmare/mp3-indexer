<?php

class Mp3Indexer_Scanner
{
	public function __construct(
		RecursiveIteratorIterator $iterator,
		sfEventDispatcher $dispatcher,
		sfEvent $event
	) {
		$this->_iterator = $iterator;
		$this->_event = $event;
		$this->_dispatcher = $dispatcher;
	}

	public function scan()
	{
		return $this->_recurse($this->_iterator);
	}

	private function _recurse($root)
	{
		foreach ($root AS $audioFile)
		{
			if ($root->hasChildren()) {
				$this->_recurse($root->getChildren());
			} else {
				// create event and notify on audio files
				$event = clone $this->_event;
				$event['file'] = $audioFile;
				$this->_dispatcher->notifyUntil($event);
			}
		}
	}
}
