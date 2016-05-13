<?php
namespace ezMongo\MongoBundle\Adapter;

use MongoDB\Model\BSONDocument;

/**
 * Class BsonToArrayAdapter
 *
 * @package ezMongo\MongoBundle\Adapter
 */
class BsonToArrayAdapter implements MongoDocumentAdapter
{
    /**
     * {@inheritdoc}
     */
    public function adapt(BSONDocument $document)
    {
        return $document;
    }
}
