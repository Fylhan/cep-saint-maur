<?php
namespace Model;

use PicoDb\Table;

class Upload extends Base
{

    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'cep_upload';

    const TYPE_FILE = 0;

    const TYPE_IMG = 1;

    public function query()
    {
        $query = $this->db->table(self::TABLE)
            ->columns('id', 'title', 'filename', 'thumb', 'type', 'date')
            ->orderBy('title', Table::SORT_ASC);
        return $query;
    }

    /**
     * Get by the id
     *
     * @access public
     * @param integer $id
     *            Id
     * @return array
     */
    public function getById($id)
    {
        return $this->db->table(self::TABLE)
            ->eq('id', $id)
            ->findOne();
    }

    /**
     * @param integer $type Type of file: self::TYPE_FILE or self::TYPE_IMG
     * @return array
     */
    public function getAll($type = null)
    {
        $qry = $this->query();
        if (null !== $type) {
            $qry->eq('type', $type);
        }
        return $qry->findAll();
    }

    /**
     * Prepare
     *
     * @access public
     * @param array $values
     *            Form values
     * @return bool
     */
    public function prepare(array $values)
    {
        $values['id'] = intval(@$values['id']);
        $values['date'] = time();
        unset($values['ack']);
        unset($values['dir']);
        return $values;
    }

    /**
     * Update
     *
     * @access public
     * @param array $values
     *            Form values
     * @return bool
     */
    public function update(array $values)
    {
        $values = $this->prepare($values);
        if (0 === $values['id']) {
            unset($values['id']);
            $res = $this->persist(self::TABLE, $values);
            $values['id'] = $res;
        }
        else {
            $res = $this->db->table(self::TABLE)
                ->eq('id', $values['id'])
                ->save($values);
        }
        return $values['id'];
    }

    /**
     * Remove a upload
     *
     * @access public
     * @param integer $id
     *            Id
     * @return bool
     */
    public function remove($id)
    {
        $this->db->startTransaction();
        
        if (! $this->db->table(self::TABLE)
            ->eq('id', $id)
            ->remove()) {
            $this->db->cancelTransaction();
            return false;
        }
        
        $this->db->closeTransaction();
        
        return true;
    }
}
