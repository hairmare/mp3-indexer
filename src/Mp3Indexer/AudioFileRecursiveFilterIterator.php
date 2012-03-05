<?php

class Mp3Indexer_AudioFileRecursiveFilterIterator extends RecursiveFilterIterator {

    public static $FILTERS = array(
        'mp3',
    );

    public function accept() {
        return $this->hasChildren() || in_array(
            $this->current()->getExtension(),
            self::$FILTERS,
            true
        );
    }

}

