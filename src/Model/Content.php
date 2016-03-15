<?php
namespace Model;

use PicoDb\Table;

class Content extends Base
{

    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'cep_content';

    public function query()
    {
        return $this->db->table(self::TABLE)->columns('id', 'url', 'title', 'content', 'abstract', 'keywords', 'date_update');
    }

    /**
     * Date of the last update of a page
     *
     * @access public
     * @param string $url
     *            Content url
     * @return number
     */
    public function getLastUpdate($url)
    {
        $lastUpdate = $this->db->table(self::TABLE)
            ->columns('date_update')
            ->eq('url', $url)
            ->findOne();
        if (null == $lastUpdate) {
            return time();
        }
        return $lastUpdate['date_update'];
    }

    /**
     * Retrieve a page content
     *
     * @access public
     * @param string $url
     *            Content url
     * @return array
     */
    public function getByUrl($url)
    {
        return $this->query()
            ->eq('url', $url)
            ->findOne();
    }

    public function getList()
    {
        return $this->query()
            ->orderBy('title', Table::SORT_ASC)
            ->findAll();
    }

    /**
     * Prepare data to be stored on db
     *
     * @param array $values            
     * @param
     *            array
     */
    public function prepare(array $values)
    {
        $values['id'] = intval(@$values['id']);
        $values['date_update'] = time();
        return $values;
    }

    /**
     * Update a page
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
        if (! $res) {
            // TODO Add warning log
            return false;
        }
        return $values['id'];
    }
}
