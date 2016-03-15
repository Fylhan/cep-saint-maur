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
    const TABLE = DB_PREFIX . 'upload';

    public function query()
    {
        $query = $this->db->table(self::TABLE)
            ->columns('id', 'title', 'image', 'thumb', 'date')
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
     *
     * @return array
     */
    public function getAll()
    {
        return $this->query()->findAll();
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
        if (! isset($values['id']) || empty($values['id']) || 0 == $values['id']) {
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
