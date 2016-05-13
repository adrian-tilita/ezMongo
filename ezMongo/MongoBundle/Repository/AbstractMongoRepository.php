<?php
namespace ezMongo\MongoBundle\Repository;

use MongoDB\BSON\ObjectID;
use MongoDB\Collection;
use ezMongo\MongoBundle\Adapter\MongoDocumentAdapter;
use MongoDB\Model\BSONDocument;

abstract class AbstractMongoRepository implements RepositoryInterface
{
    /**
     * @var Collection
     */
    private $collectionManager;

    /**
     * @var null|MongoDocumentAdapter
     */
    private $adapter;

    /**
     * @param Collection $collection
     */
    public function addCollectionManager(Collection $collection)
    {
        $this->collectionManager = $collection;
    }

    /**
     * @return Collection
     */
    protected function getCollectionManager(): Collection
    {
        return $this->collectionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function useAdapter(MongoDocumentAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Reset any inserted adapter and return BSONDocument
     */
    public function clearAdapter()
    {
        $this->adapter = null;
    }

    /**
     * Pass the documents to an adapter if any was defined
     * @param BSONDocument $document
     * @return mixed
     */
    protected function adapt(BSONDocument $document)
    {
        if (is_null($this->adapter) === true) {
            return $document;
        }
        return $this->adapter->adapt($document);
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        $result = $this->getCollectionManager()->findOne([
            '_id' => new ObjectID($id)
        ]);
        if (is_null($result) === false) {
            return $this->adapt($result);
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {

    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getClassName()
    {

    }
}
