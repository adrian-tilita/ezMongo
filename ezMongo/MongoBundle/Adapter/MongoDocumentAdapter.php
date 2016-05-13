<?php
namespace ezMongo\MongoBundle\Adapter;

use MongoDB\Model\BSONDocument;

/**
 * Interface MongoDocumentAdapter
 *
 * @package ezMongo\MongoBundle\Adapter
 */
interface MongoDocumentAdapter
{
    /**
     * BSONDocument that needs to be adapted
     * @param BSONDocument $document
     * @return mixed
     */
    public function adapt(BSONDocument $document);
}
