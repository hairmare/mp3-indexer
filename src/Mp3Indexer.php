<?php

class Mp3Indexer
{
	public function __construct(
		Mp3Indexer_Scanner $scanner,
		Mp3Indexer_Reader $reader
	) {
		$this->_scanner = $scanner;
		$this->_reader = $reader;
	}

	public function run()
	{
		$this->_scanner->scan();
	}
}
